<?php
$login = $this->Session->read('Auth.User.User');

$reqData = $this->request->query;
$path = Router::url('/thinapp_images/staff.jpg',true);
$date =date('Y-m-d');
$reqData = $this->request->query;
if(isset($reqData['d']) && !empty($reqData)){
    $date = $reqData['d'];
}
$action = $this->request->action;


$template = $this->AppAdmin->get_app_template($login['thinapp_id']);

?>

<?php echo $this->Html->script(array('magicsuggest-min.js')); ?>
<?php echo $this->Html->css(array('magicsuggest-min.css')); ?>


<?php if($template =='THEME_1'){ ?>
<div id="script_holder">
    <div class="slide_fix billing_element">

        <div class="book_app side_bar_box_btn col-lg-12 col-md-12">
            <lable class="short_cut">Press 'A'</lable>
            <a href="<?php echo Router::url('/app_admin/add_appointment',true); ?>">
                <!--<?php echo ($action == "add_appointment")?'style="border-bottom: 3px solid #94C405;"':""; ?> -->
                <div class="content_div" >
                    <div class="dash_img">
                        <img src="<?php echo Router::url('/thinapp_images/add_appointment.png')?>">
                    </div>
                    Book Appointment
                </div>
            </a>
        </div>

        <?php if(!$this->AppAdmin->check_app_enable_permission($login['thinapp_id'],"SMART_CLINIC")){ ?>
            <div class="receipt_div side_bar_box_btn col-lg-12 col-md-12">

                <a data-type="WALK-IN" class="add_walk-in_token_sidebar"  href="javascript:void(0)" id="addAppointmentDirect">
                    <div class="content_div" >
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/prescription.png')?>">
                        </div>
                        Add Walk-In Appointment
                    </div>
                </a>
            </div>
        <?php } ?>


        <div class="my_app side_bar_box_btn col-lg-12 col-md-12">
            <lable class="short_cut">Press 'M'</lable>
            <a href="<?php echo Router::url('/app_admin/view_app_schedule',true); ?>">
                <div class="content_div">
                    <div class="dash_img">
                        <img src="<?php echo Router::url('/thinapp_images/appointment.png')?>">
                    </div>
                    My Appointment
                </div>
            </a>
        </div>

        <div class="tracker_div side_bar_box_btn col-lg-12 col-md-12">

            <a href="<?php echo Router::url('/tracker/display?t=',true).base64_encode($login['thinapp_id']); ?>" >
                <div class="content_div">
                    <div class="dash_img">
                        <img src="<?php echo Router::url('/thinapp_images/users.png')?>">
                    </div>
                    OPD Tracker
                </div>
            </a>
        </div>

        <?php if($login['thinapp_id'] != 588 ){ ?>
            <div class="receipt_div side_bar_box_btn col-lg-12 col-md-12">
                <a href="<?php echo Router::url('/app_admin/medical_product_orders',true); ?>">
                    <div class="content_div" >
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/order.png')?>">
                        </div>
                        OPD Receipts
                    </div>
                </a>
            </div>
        <?php } ?>

        <div class="receipt_div side_bar_box_btn col-lg-12 col-md-12">
            <lable class="short_cut">Press 'B'</lable>
            <a href="<?php echo Router::url('/app_admin/add_hospital_receipt_search',true); ?>">
                <div class="content_div" >
                    <div class="dash_img">
                        <img src="<?php echo Router::url('/thinapp_images/hospital_bill.png')?>">
                    </div>
                    Billing
                </div>
            </a>
        </div>

        <div class="receipt_div side_bar_box_btn col-lg-12 col-md-12">
            <!--lable class="short_cut">Press 'B'</lable-->
            <a href="<?php echo Router::url('/app_admin/list_refund',true); ?>">
                <div class="content_div" >
                    <div class="dash_img">
                        <img src="<?php echo Router::url('/thinapp_images/refund.png')?>">
                    </div>
                    Refund List
                </div>
            </a>
        </div>

        <div class="receipt_div side_bar_box_btn col-lg-12 col-md-12">
            <a href="<?php echo Router::url('/app_admin/setting_print_prescription',true); ?>">
                <div class="content_div" >
                    <div class="dash_img">
                        <img src="<?php echo Router::url('/thinapp_images/prescription.png')?>">
                    </div>
                    Prescription Setting
                </div>
            </a>
        </div>

        <div class="tracker_div side_bar_box_btn col-lg-12 col-md-12">
            <a href="<?php echo Router::url('/tracker/tracker_setting',true); ?>" >
                <div class="content_div">
                    <div class="dash_img">
                        <img src="<?php echo Router::url('/thinapp_images/users.png')?>">
                    </div>
                    Tracker Setting
                </div>
            </a>
        </div>


    </div>

    <div class="modal fade"  id="search_cus_modal_without_token" role="dialog" keyboard="true"></div>

    <style>
        #search_cus_modal_without_token .modal-dialog{
            width:700px !important;
        }
    </style>

    <style>
        .short_cut{
            font-size: 10px;
            float: left;
            position: absolute;
            left: -12px;
            transform: rotate(-90deg);
            top: 26px;
            font-weight: 600;
            color: red;
        }
        .Myaccount-left {
            width: 100%;
        }
        .side_bar_box_btn {
            margin-bottom: 1px;
            margin-top: 1px;
            text-align: center;
            display: block;
            padding: 2px 2px;
            border: 1px solid #dedada;
        }
        .content_div {
            border-radius: 1em;
        }
        .slide_fix {
            width: 9%;
            float: left;
            position: fixed;
            left: 0;
            z-index: 10;
            padding: 0px 0px;
            background: #eee;
            border-radius: 0px 0px 10px 0px;
            border: 0px solid rgba(148, 196, 5, 1);
            top:71px;
            border-right: 1px solid #d2d6de;
        }
        .main_block {
            float: right;
        }
        element {
        }
        .billing_element .content_div, .billing_dashboard .content_div {

            min-height: unset;

        }
        .dash_img > img {
            height: 45px;
            width: 40px;
        }
        .content_div {
            border-radius: 1em;
            line-height: 20px;
            font-size: 11px !important;
            padding: 2px 2px !important;
            border: 0px solid #94C405 !important;
        }

        #search_cus_modal_without_token .modal-dialog{
            width:700px !important;
        }
        .app-detail{
            text-align: center;
        }
        .app-detail img{
            width: 50px;
        }
        .dashboard_icon_li li {

            text-align: center;
            width: 23%;

        }
        .container {
            width: 95%;
        }
        .Btn-typ3{width:100%;}
        .modal-header {
            background-color: #03a9f5;
            color: #FFFFFF;
        }
        .close {
            margin-top: 35px;
        }
        .modal-title
        {
            text-align: center;
            font-weight: 700;
        }
        .slide_fix.billing_element {
            height: 85%;
            overflow-y: auto;
            margin-top: 6px;
        }
        .modal-footer {
            background: #eee;

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



    <div class="modal fade" id="myModalFormAddWithoutToken" role="dialog" keyboard="true">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <?php echo $this->Form->create('AppointmentCustomerStaffService',array( 'class'=>'form','id'=>'formPayAddWithoutToken')); ?>

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="text-align: center;font-weight: bold;">Payment</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="input number col-md-2">
                            <label for="opdCharge">Service</label>
                            <input type="text" readonly="readonly" class="form-control" value="OPD" placeholder="Service">
                        </div>
                        <div class="input number col-md-1">
                            <label for="opdCharge">Quantity</label>
                            <input type="number" readonly="readonly" class="form-control" min="1" value="1" placeholder="Quantity" required="true">
                        </div>
                        <div class="input number col-md-1">
                            <label for="opdCharge">Discount</label>
                            <input type="number" name="discount_opd" id="discount_opd_addWithoutToken" class="form-control" min="0" value="0"  placeholder="Discount">
                        </div>
                        <div class="input number col-md-1">
                            <label for="opdCharge">Type</label>
                            <select name="discount_opd_type" id="discount_opd_type_addWithoutToken" onclick="return false" class="form-control">
                                <option value="PERCENTAGE">Percentage</option>
                                <option value="AMOUNT">Amount</option>
                            </select>
                        </div>
                        <div class="input number col-md-1">
                            <label for="opdCharge">Tax Type</label>
                            <input type="text" readonly="readonly" class="form-control" value="No Tax" required="true" placeholder="Tax Type">
                        </div>
                        <div class="input number col-md-1">
                            <label for="opdCharge">Tax Value</label>
                            <input type="text" readonly="readonly" class="form-control" required="true" value="0" placeholder="Tax Value">
                        </div>
                        <div class="input number col-md-2">
                            <label for="opdCharge">Expiry</label>
                            <select name="quantity_id" disabled="disabled" class="form-control"><option value="">Select</option></select>
                        </div>
                        <div class="input number col-md-1">
                            <label for="opdCharge">Price</label>
                            <?php echo $this->Form->input('amount', array('type'=>'number','label'=>false,'min'=>"0" ,'required'=>true,'placeholder'=>'Price','class'=>'form-control ','id'=>"opdChargeAddWithoutToken")); ?>
                        </div>
                        <div class="input number col-md-1">
                            <label for="opdCharge">Amount</label>
                            <input type="text" readonly="readonly" class="form-control" value="0" id="amountOPDAddWithoutToken" placeholder="Amount">
                        </div>
                    </div>

                    <div class="addProductAppendAddWithoutToken">

                    </div>

                    <div class="row" >
                        <div class="input col-md-offset-11 col-md-1">
                            <button type="button" id="addMoreAddWithoutToken" class="btn btn-primary">Add More</button>
                            <span class="subText">Press '+'</span>
                        </div>
                    </div>


                </div>
                <div class="modal-footer payment">



                    <div class="input text  col-md-2 col-xs-offset-2">
                        <?php echo $this->Form->input('hospital_payment_type_id',array('id'=>'p_type_idWithoutToken','type'=>'select','label'=>'Select Payment Mode','empty'=>'Cash','options'=>$this->AppAdmin->getHospitalPaymentTypeArray($login['thinapp_id']),'class'=>'form-control cnt')); ?>
                    </div>
                    <div class="input text  col-md-2">
                        <?php echo $this->Form->input('payment_type_name',array('id'=>'p_type_nameWithoutToken','type'=>'hidden','label'=>false,'value'=>'CASH')); ?>

                        <?php echo $this->Form->input('payment_description',array('id'=>'p_descWithoutToken','type'=>'text','label'=>'Payment Description','class'=>'form-control cnt')); ?>
                    </div>

                    <div class="input text  col-md-2">
                        <?php echo $this->Form->input('tot',array('readonly'=>'readonly','type'=>'text','label'=>'Total Amount','class'=>'form-control total_lblWithoutToken')); ?>
                    </div>
                    <div class="input text  col-md-2">
                        <label>&nbsp;</label>
                        <?php echo $this->Form->input('id', array('type'=>'hidden','label'=>false,'required'=>true,'class'=>'form-control ','id'=>"idContainerAddWithoutToken")); ?>
                        <?php echo $this->Form->submit('Pay Now',array('class'=>'Btn-typ3','id'=>'addPaySubmitAddWithoutToken')); ?>
                    </div>


                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>

    </div>


    <script>




        var base = "<?php echo Router::url('/app_admin/',true); ?>";

        var action = "<?php echo $this->request->params['action']; ?>";

        function bindEvent(){
            if(action != 'add_appointment' && action != 'view_staff_app_schedule' && action !='medical_product_orders'){
                $(document).off('keypress');
                $(document).on('keypress',  function (e) {
                    if(!$('.modal').is(':visible') && e.target.type !='text' && e.target.type !='password') {
                        clickShortCutEvent(e);
                    }
                });
            }
        }

        bindEvent();

        function clickShortCutEvent(e){

            var code = (e.keyCode ? e.keyCode : e.which);

            console.log(code);
            if(code==97){
                /*key code = A*/
                e.preventDefault();
                window.location.href = base+"add_appointment";


            }else if(code == 98){
                /*key code = B*/
                e.preventDefault();
                window.location.href = base+"add_hospital_receipt_search";


            }else if(code == 109){
                /*key code = M*/
                e.preventDefault();
                window.location.href = base+"view_app_schedule";



            }else if(code == 100){

                $("#addAppointmentDirect").trigger('click');
                e.preventDefault();
            }
        }
        function refreshProduct() {
            $.ajax({
                type: 'POST',
                url: baseurl + "app_admin/add_admin_book_appointment",
                success: function (data) {
                    $("#script_holder").html(data);
                }
            });
        }

    </script>



</div>
<?php }else{ ?>

	<div class="total_earning_div">
        <button id="today_collection" class="btn btn-info" style="margin: 5px;padding: 2px 6px;"><i class="fa fa-inr"></i> Show Updated Collection</button>
        <ul id="collection_ul"></ul>
    </div>
    
    
    <div id="script_holder">
        <div class="slide_fix billing_element">



            <div class="book_app side_bar_box_btn col-lg-12 col-md-12">
                <a href="<?php echo Router::url('/app_admin/dashboard',true); ?>">
                    <div class="content_div" >
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/dashboard_icon.png')?>">
                        </div>
                        Dashboard
                    </div>
                </a>
            </div>







            <div class="book_app side_bar_box_btn col-lg-12 col-md-12">
                <lable class="short_cut">Press 'A'</lable>
                <a href="<?php echo Router::url('/app_admin/add_appointment',true); ?>">
                    <!--<?php echo ($action == "add_appointment")?'style="border-bottom: 3px solid #94C405;"':""; ?> -->
                    <div class="content_div" >
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/add_appointment.png')?>">
                        </div>
                        Book Appointment
                    </div>
                </a>
            </div>

            <?php if(!$this->AppAdmin->check_app_enable_permission($login['thinapp_id'],"SMART_CLINIC")){ ?>
                <div class="receipt_div side_bar_box_btn col-lg-12 col-md-12">
                    <a data-type="WALK-IN" class="add_walk-in_token_sidebar"  href="javascript:void(0)" id="addAppointmentDirect">
                        <div class="content_div" >
                            <div class="dash_img">
                                <img src="<?php echo Router::url('/thinapp_images/prescription.png')?>">
                            </div>
                            Add Walk-In Appointment
                        </div>
                    </a>
                </div>
            <?php } ?>


            <div class="my_app side_bar_box_btn col-lg-12 col-md-12">
                <lable class="short_cut">Press 'M'</lable>
                <a href="<?php echo Router::url('/app_admin/view_app_schedule',true); ?>">
                    <div class="content_div">
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/appointment.png')?>">
                        </div>
                        My Appointment
                    </div>
                </a>
            </div>


            <div class="tracker_div side_bar_box_btn col-lg-12 col-md-12">
                <a href="<?php echo Router::url('/tracker/display?t='.base64_encode($login['thinapp_id']),true); ?>">
                    <div class="content_div">
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/users.png',true); ?>">
                        </div>
                        OPD Tracker
                    </div>
                </a>
            </div>



            <div class="receipt_div side_bar_box_btn col-lg-12 col-md-12">
                <lable class="short_cut">Press 'B'</lable>
                <a href="<?php echo Router::url('/app_admin/add_hospital_receipt_search',true); ?>">
                    <div class="content_div" >
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/hospital_bill.png')?>">
                        </div>
                        Billing
                    </div>
                </a>
            </div>

            <div class="receipt_div side_bar_box_btn col-lg-12 col-md-12">
                <!--lable class="short_cut">Press 'B'</lable-->
                <a href="<?php echo Router::url('/app_admin/list_refund',true); ?>">
                    <div class="content_div" >
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/refund.png')?>">
                        </div>
                        Refund List
                    </div>
                </a>
            </div>

            <div class="receipt_div side_bar_box_btn col-lg-12 col-md-12" style="width: 135px;">
                <a class="btn btn-xs extra_link" href="<?php echo Router::url('/app_admin/setting_print_prescription',true); ?>">
                    <i  class="fa fa-gear"></i> Prescription Setting
                </a>

                <a class="btn btn-xs extra_link" href="<?php echo Router::url('/tracker/tracker_setting',true); ?>" >
                    <i class="fa fa-line-chart" aria-hidden="true"></i> Tracker Setting
                </a>


                <?php if($login['thinapp_id'] != 588 ){ ?>
                    <a class="btn btn-xs extra_link" href="<?php echo Router::url('/app_admin/medical_product_orders',true); ?>">
                        <i class="fa fa-files-o" aria-hidden="true"></i> OPD Receipts
                    </a>
                <?php } ?>


                <a class="btn btn-xs extra_link" href="<?php echo Router::url('/app_admin/logout',true); ?>" >
                    <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
                </a>


            </div>










        </div>

        <div class="modal fade"  id="search_cus_modal_without_token" role="dialog" keyboard="true"></div>

        <style>
            #search_cus_modal_without_token .modal-dialog{
                width:700px !important;
            }
        </style>

        <style>


            .short_cut{
                display: none;
            }

            .Myaccount-left {
                width: 100%;
            }

            .content_div {
                border-radius: 1em;
            }


            element {
            }
            .billing_element .content_div, .billing_dashboard .content_div {

                min-height: unset;

            }



            #search_cus_modal_without_token .modal-dialog{
                width:700px !important;
            }
            .app-detail{
                text-align: center;
            }
            .app-detail img{
                width: 50px;
            }
            .dashboard_icon_li li {

                text-align: center;
                width: 23%;

            }
            .container {
                width: 95%;
            }
            .Btn-typ3{width:100%;}
            .modal-header {
                background-color: #03a9f5;
                color: #FFFFFF;
            }
            .close {
                margin-top: 35px;
            }
            .modal-title
            {
                text-align: center;
                font-weight: 700;
            }

            .modal-footer {
                background: #eee;

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



        <div class="modal fade" id="myModalFormAddWithoutToken" role="dialog" keyboard="true">

            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <?php echo $this->Form->create('AppointmentCustomerStaffService',array( 'class'=>'form','id'=>'formPayAddWithoutToken')); ?>

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="text-align: center;font-weight: bold;">Payment</h4>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="input number col-md-2">
                                <label for="opdCharge">Service</label>
                                <input type="text" readonly="readonly" class="form-control" value="OPD" placeholder="Service">
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Quantity</label>
                                <input type="number" readonly="readonly" class="form-control" min="1" value="1" placeholder="Quantity" required="true">
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Discount</label>
                                <input type="number" name="discount_opd" id="discount_opd_addWithoutToken" class="form-control" min="0" value="0"  placeholder="Discount">
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Type</label>
                                <select name="discount_opd_type" id="discount_opd_type_addWithoutToken" onclick="return false" class="form-control">
                                    <option value="PERCENTAGE">Percentage</option>
                                    <option value="AMOUNT">Amount</option>
                                </select>
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Tax Type</label>
                                <input type="text" readonly="readonly" class="form-control" value="No Tax" required="true" placeholder="Tax Type">
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Tax Value</label>
                                <input type="text" readonly="readonly" class="form-control" required="true" value="0" placeholder="Tax Value">
                            </div>
                            <div class="input number col-md-2">
                                <label for="opdCharge">Expiry</label>
                                <select name="quantity_id" disabled="disabled" class="form-control"><option value="">Select</option></select>
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Price</label>
                                <?php echo $this->Form->input('amount', array('type'=>'number','label'=>false,'min'=>"0" ,'required'=>true,'placeholder'=>'Price','class'=>'form-control ','id'=>"opdChargeAddWithoutToken")); ?>
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Amount</label>
                                <input type="text" readonly="readonly" class="form-control" value="0" id="amountOPDAddWithoutToken" placeholder="Amount">
                            </div>
                        </div>

                        <div class="addProductAppendAddWithoutToken">

                        </div>

                        <div class="row" >
                            <div class="input col-md-offset-11 col-md-1">
                                <button type="button" id="addMoreAddWithoutToken" class="btn btn-primary">Add More</button>
                                <span class="subText">Press '+'</span>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer payment">



                        <div class="input text  col-md-2 col-xs-offset-2">
                            <?php echo $this->Form->input('hospital_payment_type_id',array('id'=>'p_type_idWithoutToken','type'=>'select','label'=>'Select Payment Mode','empty'=>'Cash','options'=>$this->AppAdmin->getHospitalPaymentTypeArray($login['thinapp_id']),'class'=>'form-control cnt')); ?>
                        </div>
                        <div class="input text  col-md-2">
                            <?php echo $this->Form->input('payment_type_name',array('id'=>'p_type_nameWithoutToken','type'=>'hidden','label'=>false,'value'=>'CASH')); ?>

                            <?php echo $this->Form->input('payment_description',array('id'=>'p_descWithoutToken','type'=>'text','label'=>'Payment Description','class'=>'form-control cnt')); ?>
                        </div>

                        <div class="input text  col-md-2">
                            <?php echo $this->Form->input('tot',array('readonly'=>'readonly','type'=>'text','label'=>'Total Amount','class'=>'form-control total_lblWithoutToken')); ?>
                        </div>
                        <div class="input text  col-md-2">
                            <label>&nbsp;</label>
                            <?php echo $this->Form->input('id', array('type'=>'hidden','label'=>false,'required'=>true,'class'=>'form-control ','id'=>"idContainerAddWithoutToken")); ?>
                            <?php echo $this->Form->submit('Pay Now',array('class'=>'Btn-typ3','id'=>'addPaySubmitAddWithoutToken')); ?>
                        </div>


                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>

        </div>


        <script>




            var base = "<?php echo Router::url('/app_admin/',true); ?>";

            var action = "<?php echo $this->request->params['action']; ?>";

            function bindEvent(){
                if(action != 'add_appointment' && action != 'view_staff_app_schedule' && action !='medical_product_orders'){
                    $(document).off('keypress');
                    $(document).on('keypress',  function (e) {
                        if(!$('.modal').is(':visible') && e.target.type !='text' && e.target.type !='password') {
                            clickShortCutEvent(e);
                        }
                    });
                }
            }

            bindEvent();

            function clickShortCutEvent(e){

                var code = (e.keyCode ? e.keyCode : e.which);

                console.log(code);
                if(code==97){
                    /*key code = A*/
                    e.preventDefault();
                    window.location.href = base+"add_appointment";


                }else if(code == 98){
                    /*key code = B*/
                    e.preventDefault();
                    window.location.href = base+"add_hospital_receipt_search";


                }else if(code == 109){
                    /*key code = M*/
                    e.preventDefault();
                    window.location.href = base+"view_app_schedule";



                }else if(code == 100){

                    $("#addAppointmentDirect").trigger('click');
                    e.preventDefault();
                }
            }
            function refreshProduct() {
                $.ajax({
                    type: 'POST',
                    url: baseurl + "app_admin/add_admin_book_appointment",
                    success: function (data) {
                        $("#script_holder").html(data);
                    }
                });
            }


			
            $(document).off('click','#today_collection');
            $(document).on('click','#today_collection',function(e){


                if($("#search_cus_modal, #myModalFormAdd").length ==0){
                    e.preventDefault();
                    var $btn = $(this);
                    var obj = $(this);
                    $.ajax({
                        type: 'POST',
                        url: baseurl + "app_admin/today_collection",
                        data: {
                            'di':btoa($("#doctor_drp").val()),
                            'ai':btoa($("#address_drp").val()),
                            'bd':($("#appointment_date").val()),
                        },
                        beforeSend: function () {
                            $btn.button('loading').html('<i class="fa fa-spinner fa-spin state_spin"></i> Show Updated Collection')
                        },
                        success: function (response) {
                            $btn.button('reset');
                            $("#collection_ul").html(response)
                        },
                        error: function (data) {
                            $btn.button('reset');
                            //alert("Sorry something went wrong on server.");
                        }
                    });
                }

                
            });

            setInterval(function () {
                $("#today_collection").trigger('click');
            },50000);
            
            
        </script>



    </div>
<?php } ?>

