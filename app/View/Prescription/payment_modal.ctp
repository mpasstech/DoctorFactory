<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" data-pi="<?= base64_encode($patient_id); ?>" data-pt="<?=$patient_type; ?>" >
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <div class="modal-dialog modal-md" style="width: 55%;" >
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-body">
                <div class="container" >
                    <div class="row">
                        <div class="col-xs-12" style="padding: 0px;">
                            <ul class="nav nav-tabs">
                                <li class="active" ><a style="border-radius: 20px 0px 0px 0px;" data-toggle="tab" href="#nav-payment">Payment</a></li>
                                <li  ><a id="payment_history_tab" style="border-radius: 0px 20px 0px 0px;" data-toggle="tab" href="#nav-payment-history">History</a></li>
                            </ul>
                            <div id="tab_content_box" class="tab-content">
                                <div id="nav-payment" class="tab-pane fade in active">
                                    <div class="inner_data_container top_section">
                                        <label> <?php echo @$doctor_data['name']; ?></label>
                                        <?php $address_list = $this->AppAdmin->get_app_address($login['thinapp_id']); ?>
                                        <select id="address_drp">
                                            <?php if(!empty($address_list)){ foreach($address_list as $key =>$address){ ?>
                                                <option value="<?php echo $key; ?>"><?php echo $address; ?></option>
                                            <?php }} ?>
                                        </select>

                                    </div>
                                    <div class="inner_data_container payment_fields">
                                        <p>
                                            <button type="button" class="btn btn-xs btn-success select_service" style="height: 30px;"><i class="fa fa-plus"></i> Service</button>
                                        </p>

                                        <div class="service" style="display: none;">
                                            <ul id="service_list_ul">

                                            </ul>

                                            <li class="extra_li" style="color:#0267FF;">
                                                <label class="service_name_label"><i class="fa fa-money"></i> Previous Pending Amount</label>
                                                <label class="service_amount_label"><i class="fa fa-inr"></i>  <span data-val="<?php echo !empty($data['due_amount'])?$data['due_amount']:0.00; ?>" class="prv_balance"><?php echo !empty($data['due_amount'])?$data['due_amount']:0.00; ?></span></label>
                                            </li>
                                            <li class="extra_li" style="display: none;">
                                                <label class="service_name_label total_amt_lbl">Current Charges</label>
                                                <label class="service_amount_label total_amt_lbl_value"><i class="fa fa-inr"></i>  <span data-amt="0" class="current_charges">0</span></label>
                                            </li>

                                            <li class="extra_li">
                                                <label class="service_name_label total_amt_lbl">Total Amount</label>
                                                <label class="service_amount_label total_amt_lbl_value"><i class="fa fa-inr total_rupees_icon"></i>  <span class="total_charges" data-amt="0.00"> 0.00</span></label>
                                            </li>

                                        </div>



                                        <div class="col-xs-12 footer_section" style="display: none;">
                                            <label class="radio_lbl"><input type="radio" name="payment_type" value="FULL" checked> Full Payment</label>
                                            <label  class="radio_lbl"><input type="radio" name="payment_type" value="PART"> Part Payment</label>
                                            <label style="color: #000;font-size: 15px;"><i class="fa fa-inr"></i> <input type="number" min="1" step="any" id="paid_amount" placeholder="Enter Amount"></label>
                                            <button type="button" class="btn btn-xs btn-success save_payment_btn" style="height: 30px;">Save Payment</button>

                                        </div>



                                    </div>




                                </div>
                                <div id="nav-payment-history" class="tab-pane fade">


                                    <?php if(!empty($history)){ foreach ($history as $key => $his){ ?>

                                        <div class="list_item">
                                            <span class="payment_date"><?php echo date('D, d M Y',strtotime($his['created'])); ?></span>

                                            <label class="color_lbl">Charged Amount</label>
                                            <label class="total_lbl"><i class="fa fa-inr total_rupees_icon"></i><span class="total_charges"> <?php echo $his['charged_amount']; ?></span></label>
                                            <label class="color_lbl">Paid Amount
                                                <span class="paid_amount_span"><i class="fa fa-inr"></i><span> <?php echo $his['paid_amount']; ?></span></span>
                                                <button class="receipt_link"  type="button" data-href="<?php echo Router::url('/prescription/print_invoice/'.base64_encode($his['id']),true);?>"><i class="fa fa-file"></i></button>
                                                <button class="print_btn_link" type="button" data-href="<?php echo Router::url('/prescription/print_invoice/'.base64_encode($his['id']),true);?>"><i class="fa fa-print"></i></button>


                                            </label>
                                            <p><?php
                                                        $service_array =  explode(",",$his['service_name']);
                                                        if(!empty($his['due_paid_amount'])){
                                                            $service_array[] = "Due Amount(".$his['due_paid_amount'].")";
                                                        }
                                                        echo implode(", ",$service_array);

                                            ?></p>
                                            <label><i class="fa fa-map-marker"></i><span> <?php echo $his['address']; ?></label>
                                        </div>

                                    <?php }}else{ ?>
                                        <p style="text-align: center; font-size: 20px;"> No payment history</p>
                                    <?php } ?>

                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>


    <div id="tttt"></div>



    <script type="text/javascript">
        $(function () {

            $(document).off('click','.cancel_payment_service_btn');
            $(document).on('click','.cancel_payment_service_btn',function(){
                $("#paymentServiceListModal").modal('hide');
            });

            $(document).on('shown.bs.modal', '#paymentServiceListModal', function () {
                $("#myTable li .service_name .select_box").each(function (index,value) {
                     var si = atob($(this).val());
                    if($("[data-si='"+si+"']").length == 1){
                        $(this).prop('checked',true);
                    }
                })
            });

            $(document).off('click','.select_service');
            $(document).on('click','.select_service',function(){
                var $btn =$(this);
                var patient_data = $("#patient_detail_object").data('key');
                patient_data = JSON.parse(patient_data);
                var appointment_id = patient_data.general_info.appointment_id;
                $.ajax({
                    type:'POST',
                    url: baseUrl+"prescription/payment_service_modal",
                    data:{ai:appointment_id},
                    beforeSend:function(){
                        $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                    },
                    success:function(result){
                        $btn.button('reset');
                        var html = $(result).filter('#paymentServiceListModal');
                        $(html).modal({backdrop: 'static', keyboard: false});

                    },
                    error: function(){
                        $btn.button('reset');
                        flash('Service',"Sorry something went wrong on server.",'left','danger');
                    }
                });

            });


            $(document).off('click','.receipt_link');
            $(document).on('click','.receipt_link',function(){
                var $btn = $(this);
                $.ajax({
                    url: $(this).attr('data-href'),
                    type:'POST',
                    beforeSend:function(){
                        $($btn).button({loadingText: '<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>'}).button('loading');
                    },
                    success: function(result){
                        $btn.button('reset');
                        var html = $(result).filter('#printInvoiceModal');
                        $(html).modal('show');



                    },error:function () {
                        $btn.button('reset');
                    }
                });

            });


            $(document).off('click','.print_btn_link');
            $(document).on('click','.print_btn_link',function(){
                var $btn = $(this);
                $.ajax({
                    url: $(this).attr('data-href'),
                    type:'POST',
                    beforeSend:function(){
                        $($btn).button({loadingText: '<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>'}).button('loading');
                    },
                    success: function(result){
                        $btn.button('reset');
                        var html = $(result).filter('#printInvoiceModal').modal('show').modal('hide');
                        //var html = $(html).modal('hide');
                        $(html).find('#print_container').printThis({
                            importCSS: true,
                            importStyle: true,
                            pageTitle: "",
                            removeInline: false,
                            printDelay: 333,
                            header: null,
                            footer: null,
                            base: false ,
                            formValues: true,
                            doctypeString: ".",
                            removeScripts: false,
                            copyTagClasses: false
                        });




                    },error:function () {
                        $btn.button('reset');
                    }
                });

            });




            function createServiceString(id,service_name,service_amt){
                var string = '<li data-si="'+id+'"><label class="service_name_label"><i class="fa fa-money"></i> '+service_name+'</label>';
                string += '<label data-amt="'+service_amt+'" class="service_amount_label"><i class="fa fa-inr"></i> <input class="service_amount_input" value="'+service_amt+'"></label><button class="remove_service_btn" type="button" style="background: none;border: none;"><i class="fa fa-close"></i></button></li>';
                return string;
            }




            $(document).off('click', '.service_selection_done_btn');
            $(document).on('click', '.service_selection_done_btn', function () {
                var string ="";
                $("#myTable li .select_box:checked").each(function (index,value) {
                   var service_name = $(this).closest("li").find('.service_name').attr("data-name");
                    var service_amt = $(this).closest("li").find('.service_amt').attr("data-amount");
                    var id = atob($(this).val());
                    string += createServiceString(id,service_name,service_amt);
                });
                $("#service_list_ul").html(string);
                showHideBox();
                $("#paymentServiceListModal").modal('hide');

            });
            $(document).off('click', '.remove_service_btn');
            $(document).on('click', '.remove_service_btn', function () {
                $(this).closest("li").remove();
                showHideBox();
            });


            $(document).off('click','.save_payment_btn');
            $(document).on('click','.save_payment_btn',function(){
                var $btn = $(this);
                if($("#service_list_ul .service_amount_input").length == 0){
                    flash("Service",'Please select service from service list', "warning",'center');
                }else{
                    var allow_save = false;
                    var paid_amount =$('#paid_amount').val();
                    paid_amount  = (paid_amount)?parseFloat(paid_amount):0;
                    var total_amount = parseFloat($(".total_charges").attr('data-amt'));
                    var type = $("input[name='payment_type']:checked").val();
                    var patient_id = $("#paymentModal").attr('data-pi');
                    var patient_type = $("#paymentModal").attr('data-pt');
                    var address_id = btoa($("#address_drp").val());
                    var charged_amount = $(".current_charges").attr('data-amt');
                    var appointment_id = "<?php echo base64_encode($appointment_id); ?>";
                    var service_array =[];
                    if(type=='FULL'){
                        paid_amount = total_amount;
                        allow_save = true;
                    }else{
                        if(paid_amount!='' && paid_amount <= total_amount){
                            allow_save = true;
                        }else{
                            if(paid_amount==''){
                                flash("Payment",'Enter paid amount', "warning",'center');
                            }else{
                                flash("Payment",'Paid amount can not be much than total amount', "warning",'center');
                            }
                        }
                    }
                    if(allow_save ===true){
                        $("#service_list_ul li").each(function (index,value) {
                            var obj = {
                                'id':$(this).attr('data-si'),
                                'service_amount':$(this).find('.service_amount_label').attr('data-amt'),
                                'service_paid_amount':$(this).find('.service_amount_input').val()
                            }
                            service_array.push(obj);
                        });
                        $.ajax({
                            url: "<?php echo Router::url('/prescription/save_payment',true);?>",
                            type:'POST',
                            data:{app_i:appointment_id,ai:address_id,pt:patient_type,pi:patient_id,sa:service_array,ta:total_amount,pa:paid_amount,ca:charged_amount},
                            beforeSend:function(){
                                $btn.button('loading');
                            },
                            success: function(response){
                                $btn.button('reset');
                                response = JSON.parse(response);
                                if(response.status == 1){
                                    flash("Payment",response.message, "success",'center');
                                    $("#paymentServiceListModal").find('#reset_service_box_btn').trigger('click');
                                    var html = $('#tab_content_box', $(response.data.update));
                                    $("#paymentModal #tab_content_box").html(html);
                                    $("#payment_history_tab").trigger('click');

                                    if(response.data.appointment){
                                        $( ".patient_list_li a.active_li" ).closest('li').replaceWith( response.data.appointment );
                                    }

                                }else{
                                    flash("Warning",response.message, "warning",'center');
                                }
                            },error:function () {
                                $btn.button('reset');
                                flash("Error",'Something went wrong on server.', "danger",'center');
                            }
                        });
                    }

               }
            });



            $(document).off('input','.service_amount_input');
            $(document).on('input','.service_amount_input',function(){
                update_amounts();
            });


            $(document).off('change',"input[name='payment_type']");
            $(document).on('change',"input[name='payment_type']",function(){
                totalEditable();
            });


            function showHideBox(){
                if($("#service_list_ul li").length > 0){
                    $("#nav-payment .service, .footer_section").show();
                }else{
                    $("#nav-payment .service, .footer_section").hide();
                }
                update_amounts();
            }

            var appointment_id = "<?php echo $appointment_id; ?>";
            if(appointment_id > 0){
                var string = createServiceString(0,'OPD',"<?php echo $opd_amount; ?>",false);
                $("#service_list_ul").html(string);
                showHideBox();
            }

            function totalEditable(){
                if($("input[name='payment_type']:checked").val() =='FULL'){
                    $("#paid_amount").attr('readonly','readonly');
                }else{
                    $("#paid_amount").removeAttr('readonly');
                }
            }

            function update_amounts(){
                var prev_amt = $(".prv_balance").attr('data-val');
                var amount = 0;
                $("#service_list_ul  .service_amount_input").each(function (index,value) {
                    amount += parseFloat($(this).val());
                });
                amount = (parseFloat(amount))?parseFloat(amount):0;
                $(".current_charges").html(amount).attr('data-amt',amount);
                var total = parseFloat(prev_amt)+parseFloat(amount);
                $(".total_charges").html(total).attr('data-amt',total);
                $("#paid_amount").val(total);
                totalEditable();

            }


        });
    </script>
    <style>

        .footer_section .radio_lbl{
            color: #0267FF;
            font-size: 17px;
            margin-right: 0px;
        }
        .footer_section{
            border-top: 1px solid;
            padding:14px 0px 0px 0px;
        }

        .footer_section label, .footer_section button{
            float: left;
            width: 25%;
        }
        #paymentModal{
            font-family: Montserrat !important;

        }
        #service_list_ul li, .extra_li{
            color:#197c0e;
            width: 80%;
            padding: 4px 0px;
            float: left;
            display: block;
        }
        .inner_data_container{
            padding: 0px 15px;
            float: left;
            display: block;
            width: 100%;
        }
        .inner_data_container.top_section{
            background: #F2F2F2;
        }
        .inner_data_container.payment_fields{
           padding-top: 10px;
           padding-bottom: 10px;
        }
        .select_service{
            height: 30px;
            background: #F15721;
            border: #F15721;
            padding: 2px 24px;
            font-size: 14px;
            border-radius: 6px;

        }

        .inner_data_container.top_section label{
            font-size: 19px;
            padding: 1px 0px;
            color: #2063A1;
            font-weight: 600;
        }



        .receipt_link, .print_btn_link{
            float: right;
            position: relative;
            right: 0px;
            top: 0;
            font-size: 20px;
            background: none;
            border: none;
            padding: 0px;
            margin: 0 1px;
            color: #131313d1;
            width: 26px;
            height: 26px;
        }




        .payment_date{
            float: right;
            color:#8b8888;
        }
        #nav-payment-history .list_item{
            padding: 2px 5px;
            margin: 2px 4px;
            box-shadow: 0px 1px 3px #c6c2c2;
        }
        #nav-payment-history, #nav-payment{
            height: 500px;
            overflow-y: auto;
        }


        .list_item label{
            width: 100%;
            display: block;
        }
        #address_drp{
            outline: none;
        }

        #service_list_ul{
            margin: 0px 0px;
            padding: 0px 0px;
            float: left;
            width: 100%;
            list-style: none;
        }
        .service_name_label{
            list-style: none;
            float: left;
            width: 70% !important;
            display: block;
            font-size: 16px;
            font-weight: 600;

        }
        .service_name_label i{
            padding-right: 10px;
        }
        .service_amount_label{
            float: left;
            width: 15% !important;
            font-weight: 600;
            font-size: 16px;
        }
        .service_amount_label i{
            margin-top: 5px;

        }

        .service_amount_input{
            outline: none;
            border: none;
            border-bottom: 1px solid;
            width: 80%;
            float: right;

        }

        .button_box{
            text-align: right;
            margin: 5px 0px;
        }

        #paid_amount{
            border: none;
            border-bottom: 1px solid;
            outline: none;
            width: 40%;
        }


        .first_box label{
            padding: 0px;
            margin: 0px;
        }
        .color_lbl{
            color:#00a900;
        }




        #nav-payment select{
            width: 100%;
            border: none;
            background: transparent;
            font-size: 15px;
            font-weight: 600;
            padding-bottom: 7px;
            color: #000;

        }

        #paymentModal .container{
            padding: 0px;
            width: 100%;
        }
        #paymentModal .modal-body{
            padding: 0px;
        }




        #paymentModal .nav-tabs>li>a{
            border-radius: 0px;
            border: none;
            font-size: 20px;
            font-weight: 600;
            color: #000;
        }

        #paymentModal .nav-tabs>li.active a{
            background: #0267FF;
            color: #fff;
            margin: 0px;

        }

        #paymentModal .nav-tabs>li{
            width: 50%;
            text-align: center;

        }





        .ser_list_action_btn a{
            padding: 5px;
            font-size: 16px;
            text-decoration: none;
        }

        .total_amt_lbl{
            padding-top: 2px;
            text-align: right;
            padding-right: 24px;
            color: #000;
        }
        .total_amt_lbl_value{

            padding-right: 24px;
            color: #000;
        }
        .save_payment_btn{
            height: 30px;
            background: #0A9E07;
            border-radius: 5px;
            font-size: 13px;
            padding: 0px 8px;
        }
    </style>


</div>
