<?php
    $booked_with_payment_success = "#78f704";
    $booked_with_payment_pending = "#fbec3a";
    $available_slots = "#FFFFFF";
    $expired_time_slots = "#927d7d";
    $refunded_appointment = "#04f3ff";
    $blocked_slots = "#9765d8";
    $emergency = "#ff0000";
    $smart_tab_login = (isset($this->request->query['d']) && $this->request->query['d']=='tab')?true:false;

    foreach($colorData AS $color){
        if($color['AppointmentSlotColor']['type'] == 'booked_with_payment_success')
        {
            $booked_with_payment_success = $color['AppointmentSlotColor']['color_code'];
        }
        else if($color['AppointmentSlotColor']['type'] == 'booked_with_payment_pending'){
            $booked_with_payment_pending = $color['AppointmentSlotColor']['color_code'];
        }
        else if($color['AppointmentSlotColor']['type'] == 'available_slots'){
            $available_slots = $color['AppointmentSlotColor']['color_code'];
        }
        else if($color['AppointmentSlotColor']['type'] == 'expired_time_slots'){
            $expired_time_slots = $color['AppointmentSlotColor']['color_code'];
        }
        else if($color['AppointmentSlotColor']['type'] == 'refunded_appointment'){
            $refunded_appointment = $color['AppointmentSlotColor']['color_code'];
        }
        else if($color['AppointmentSlotColor']['type'] == 'blocked_slots'){
            $blocked_slots = $color['AppointmentSlotColor']['color_code'];
        }
        else if($color['AppointmentSlotColor']['type'] == 'emergency'){
            $emergency = $color['AppointmentSlotColor']['color_code'];
        }

    }

?>

<style>


.app_booking_lbl{
            background: red;
            color: #fff;
            padding: 0 8px;
            font-size: 1rem;
            position: absolute;
            right: 5px;
    }
    
    
    #patient_history_modal {
        z-index: 99999999;
    }

    #patient_history_modal > .modal-dialog.modal-sm {

        height: 90%;

    }
    .modal-contents {

        height: 100%;

    }
    .modal-bodys {

        height: 100%;

    }
    #ifram {

        width: 100%;
        border: none;
        height: 90%;

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
.modal-footer {
    background: #eee;
}
    #custom_error_msg{
        font-size: 20px;
        text-align: center;
        color: #FF1D11;
    }


    .slot_date {
        height: 42px;
    }

    .option_div .form-control {

        height: 35px !important;
        padding: 6px 3px !important;
    }



    .option_btn{
        position: absolute;
    }


    .option_bar_btn, .close_option_btn{
        display: none;
    }


    .custom_form_box, .slot_list_div{
        padding: 0px 0px;
        min-height: 500px;
    }
    @media (pointer:none), (pointer:coarse) {



        .user-img-class .add_sub_token{
            font-size: 8px;
            line-height: 1;
            border-radius: 3px;
        }



        .option_btn{
            background: none;
            width: 35px;
            height: 35px;
            border: 1px solid #ccc;
            padding: 9px 0px;
            position: absolute;
            border-radius: 29px;
            background: #fff;
            right: 6px;
            bottom: 2px;
            z-index: 6;
            font-size: 16px;
        }
        .option_bar_btn{
            display: block;
        }

        .slot_blok li{
            padding: 0 4px !important;
        }

        .payment-status-container, .payment-type-container {
            font-size: 10px;
        }



        .token_class {

            z-index: 9999999;
        }

    }


        @media (min-width: 768px) and (max-width: 1024px) {

            .slot_blok li{
                width: 25%;
                font-size:11px;
            }

            .token_class{
                font-size: 11px !important;
                width: 50px !important;
            }
            .user-payment-info{
                padding: 3px 0px !important;
            }
            .fixed_button {
                top: -180px !important;
            }
            .user-info-taken label{
                line-height: 15px !important;
            }
            .payment-amount-container{
                font-size: 8px !important;
            }
            .total_fee_lbl{
            	left :10px !important;
            }
            .slot_blok li .button-div button{
            	height:11px !important;
                font-size:10px !important;
            }
             .top_slice{
                font-size: 8px !important;
                margin:0px !important;
                padding:1px 0px !important;
                line-height:12px !important;
            }
             .top_slice span{
                font-size: 7px !important;
                line-height:8px !important;
            }
            .custom_message_input{
            	width:80% !important;
            }
            .hidden_btn{
            	font-size:8px !important;
            }

        }


    .not_available_doctor {
        font-size: 5px;
        padding: 9px 7px;
        text-align: center;
        width: 50%;
        position: absolute;
        top: 30px;
        background: #e38d13d4;
        border: 1px solid #c77808;
        left: 23%;
        border-radius: 27px;
        z-index: 2;
    }

    .not_available_doctor h3{
        margin: 0px !important;
    }


    .label_paragraph{
        width: 100%;
        display: block;
        float: left;

    }
    .indicator_label{
        width: 100%;
        display: block;
        float: left;
        list-style: none;
        border-bottom: 1px solid;
        background: #e9e6e6;
        padding: 5px 0px;
        margin-bottom:  10px;
    }
    .indicator_label li{
        width: auto;
        display: block;
        float: left;
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 600;
        padding: 0px 2px;

    }

    .indicator_label li input{
        width: 15px;
        height: 15px;
        display: block;
        float: left;
        margin: 3px 4px;
        padding: unset;
    }

    #blog_slot_btn{
        position: relative;
        float: right;

    }

 .list_btn_tab li {
        padding: 0 3px;
    }
    .list_btn_tab {
        list-style: none;
        float: left;
        display: inline-flex;
        margin: 0;
        padding: 0 5px;
    }
    .btn-selected {
        background-image: none !important;
        background: #FFF !important;
        color: #000 !important;
        border: 5px solid #cf6c6c;
        box-shadow: 2px 4px 5px #918686;
    }


       .top_navigation_btn{
        position: fixed;
        text-align: center;
        margin: 0% 49%;
        z-index: 99999;
        font-size: 17px;
        width: 26px;
        height: 26px;
    }

    .appointment_type_btn button{
        padding:7px;
    }





</style>

<?php
$login = $this->Session->read('Auth.User');
$filter_array = $this->AppAdmin->get_doctor_filter_appointment_array($login['User']['thinapp_id']);
$staff_data = $this->AppAdmin->get_doctor_by_id($login['AppointmentStaff']['id'],$login['User']['thinapp_id']);

echo $this->Html->script(array('jquery.maskedinput-1.2.2-co.min.js','bootstrap-popup-confirmation.min.js','comman.js'));


?>


<a href="javascript:void(0)" style="display: none;" class="top_navigation_btn"><i class="fa fa-arrow-down"></i> </a>
<div class="Home-section-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">

                    <?php if(!$smart_tab_login){ ?>
                        <?php echo $this->element('app_admin_book_appointment'); ?>
                    <?php } ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box right_box_div" style="margin: 13px 0px;">
                        <?php if(!$smart_tab_login){ ?>
                            <ul class="indicator_label">
                                <li>
                                    <input type="color" class="color_picker" data-value="booked_with_payment_success" value="<?php echo $booked_with_payment_success; ?>">
                                    Booked With Payment Success
                                </li>
                                <li>
                                    <input type="color" class="color_picker" data-value="booked_with_payment_pending" value="<?php echo $booked_with_payment_pending; ?>">
                                    Booked With Payment Pending
                                </li>
                                <li>
                                    <input type="color" class="color_picker" data-value="available_slots" value="<?php echo $available_slots; ?>">
                                    Available Slots
                                </li>

                                <li>
                                    <input type="color" class="color_picker" data-value="expired_time_slots" value="<?php echo $expired_time_slots; ?>">
                                    Expired Time Slots
                                </li>
                                <li>
                                    <input type="color" class="color_picker" data-value="refunded_appointment" value="<?php echo $refunded_appointment; ?>">
                                    Refunded Appointment
                                </li>
                                <li>
                                    <input type="color" class="color_picker" data-value="blocked_slots" value="<?php echo $blocked_slots; ?>">
                                    Blocked Slots
                                </li>
                                <li>
                                    <input type="color" class="color_picker" data-value="emergency" value="<?php echo $emergency; ?>">
                                    Emergency
                                </li>
                                <?php if ($login['USER_ROLE'] == 'ADMIN' || ( $login['USER_ROLE'] == 'RECEPTIONIST' && $staff_data['allow_block_appointment_slot'] == 'YES') ) { ?>
                                    <button type="button" id="blog_slot_btn" class="btn btn-danger btn-xs"><i class="fa fa-ban"></i> Block Slot </button>
                                <?php } ?>


                            </ul>
                        <?php } ?>
                        <ul style="display: <?php echo (!$smart_tab_login)?'':'none'; ?>" class="list_btn_tab appointment_type_btn">
                           
                             <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"SMART_CLINIC")){ ?>
                                <li>
                                    <button type="button" id="show-book-appointment" class="btn-list-appointment btn btn-warning btn-selected"><i class="fa fa-wheelchair"></i> Book Appointment</button>
                                </li>
                                <li>
                                    <button type="button" id="live-tracker" class="btn-list-appointment btn btn-primary"><i class="fa fa-hourglass-half"></i> Live Tracker</button>
                                </li>
                            <?php } ?>

						<li>
                                <a style="padding: 7px;" href="<?php echo Router::url('/app_admin/lite_book_appointment',true); ?>"  class="btn btn-success"><i class="fa fa-bookmark"></i> Switch to Fast Appointment Screen</a>
                            </li>



                        </ul>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 option_div" style="padding: 0px;">
                            <div class="form-group">

                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3  append_doctor">
                                    <label>Select Doctor</label>
                                    <?php echo $this->Form->input('doctor',array('id'=>'doctor_drp','type'=>'select','label'=>false,'options'=>array(),'class'=>'form-control')); ?>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3  append_service">
                                    <label>Select Service</label>
                                    <?php echo $this->Form->input('service',array('id'=>'service_drp','type'=>'select','label'=>false,'empty'=>'Select address','options'=>array(),'class'=>'form-control')); ?>

                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3  append_address">

                                    <label>Select Address</label>
                                    <?php echo $this->Form->input('address',array('id'=>'address_drp','type'=>'select','label'=>false,'empty'=>'Select address','options'=>array(),'class'=>'form-control')); ?>

                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3  append_date">

                                    <label>Select Date</label>
                                    <div class="input-group">
                                        <span class="input-group-addon app_slot_span"><span class="glyphicon glyphicon-calendar"></span></span>
                                        <input data-date-format = 'dd/mm/yyyy' type="text" id="appointment_date" class="form-control" placeholder="Date" name="appointment_date">
                                    </div>

                                </div>


                                <div class="append_appointment">

                                </div>
                                <div class="col-md-12 loading_box_controll">
                                    <div class="loading_div_app">
                                        <span class="fa fa-spinner fa-spin slot_loading"></span> &nbsp; <lable class="app_loader_msg"></lable>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 slot_list_div">
                            <div id="custom_error_msg"></div>
                            <div id="load_slot_div"></div>
                        </div>
                    </div>

                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>

    <a  style="display: <?php echo (!$smart_tab_login)?'block':'none'; ?>;color:#e2e2e299;float: right;" href="<?php echo Router::url('/app_admin/add_appointment_tmp',true)?>" >Automation Link</a>
</div>

<div class="modal fade" id="reschedule" role="dialog" keyboard="true"></div>

<div class="modal fade" id="myModalCancelForm" role="dialog">
    <div class="modal-dialog modal-md">

        <div class="modal-content">

            <form method="post" id="cancelForm">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cancel</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="input number col-md-12">
                            <label style="width: 100%;" for="opdCharge">Cancellation Reason <span style="float: right;color: red;" id="char_count">0/70</span></label>
                            
                            <input type="hidden" id="cancelIdHolder" name="id" >
                            <textarea maxlength = "70" name="message" id="cancelMessage" class="form-control" placeholder="Cancellation Reason"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer payment">
                    <div class="submit"><input class="Btn-typ3" value="Submit" type="submit"></div>
                </div>

            </form>

        </div>
    </div>
</div>


<div class="modal fade" id="patient_history_modal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content modal-contents">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">History</h4>
            </div>
            <div class="modal-body modal-bodys" id="modal_body" style="padding: 0; float: left;width: 100%;">
                <iframe id="ifram"></iframe>
            </div>
        </div>
    </div>
</div>


<script>
    var tokenSearch = "";
    var tabType = "";
    $(document).ready(function(){

$(".top-info").hide();
        clickedIndex = -1;


        blink_interval = setInterval(blink_text, 1000);
        function blink_text() {
            $('.LIVE_TRACKER_CURRENT_TOKEN_LI').fadeOut(660);
            $('.LIVE_TRACKER_CURRENT_TOKEN_LI').fadeIn(660);
        }


		$(document).on('input','#cancelMessage',function(e){
            var len = $(this).val().length;
            $("#char_count").html(len+"/70");
        });

        var smart_tracker = "<?php echo (!$smart_tab_login)?'NO':'YES' ?>";
        if(smart_tracker=='YES'){
            tabType = "LIVE_TRACKER";
            $(".header").hide();
            $("#refresh_page_li").show();
        }




        var tempArray = <?php echo json_encode($filter_array); ?>;
        function create_doctor_dropdown(){

            if(tempArray){
                var doctor_string = "";
                for (var key in tempArray) {
                    if (tempArray.hasOwnProperty(key)) {
                        if (tempArray.hasOwnProperty(key)) {
                            var doctor_name = tempArray[key].doctor_name;
                            doctor_string += "<option value='"+key+"'>"+doctor_name+"</option>";
                        }
                    }
                }
                $("#doctor_drp").html(doctor_string);
                setTimeout(function(){$("#doctor_drp").trigger('change');},10);
            }


       }
        function create_service_dropdown(doctor_id){

            var option="";
            for (var doctor_key in tempArray) {
                if (tempArray.hasOwnProperty(doctor_key)) {
                    if(doctor_key == doctor_id){
                        object = tempArray[doctor_key]['service'];
                        for (var service_key in object) {
                            if (object.hasOwnProperty(service_key)) {
                               option += "<option data-value='"+object[service_key].service_amount+"' value='"+service_key+"'>"+object[service_key].service_name+"</option>";
                            }
                        }
                        $("#service_drp").html(option);
                        setTimeout(function(){$("#service_drp").trigger('change');},30);
                        break;
                    }
                }
            }

        }
        function create_address_dropdown(service_id,doctor_id){
            var option="";
            for (var doctor_key in tempArray) {
                if (tempArray.hasOwnProperty(doctor_key)) {
                    if(doctor_key == doctor_id){
                        object = tempArray[doctor_key]['service'];
                        for (var service_key in object) {
                            if (object.hasOwnProperty(service_key)) {
                                if(service_key == service_id){
                                    object_2 = object[service_key]['address'];
                                    for (var address_key in object_2) {
                                        if (object_2.hasOwnProperty(address_key)) {
                                            option += "<option value='"+address_key+"'>"+object_2[address_key].address+"</option>";
                                        }
                                    }
                                    $("#address_drp").html(option);
                                    setTimeout(function(){$("#address_drp").trigger('change');},10);

                                }
                            }

                        }
                        break;
                    }
                }
            }


        }
        create_doctor_dropdown();

        $(document).on('change','#doctor_drp',function(e){

            $(".tracker_msg_ul").html('');
            var doctor_id = $(this).val();
            create_service_dropdown(doctor_id);
        });
        $(document).on('change','#service_drp',function(e){
            var service_id = $("#service_drp").val();
            var doctor_id = $('#doctor_drp').val();
            create_address_dropdown(service_id,doctor_id);
        });
        $(document).on('change','#address_drp',function(e){
            $("#appointment_date").trigger('changeDate');
        });

        $("#appointment_date").datepicker('setDate',new Date()).on('changeDate', function(ev){
            $('#appointment_date').datepicker('hide');
                loadingSlotTab = false;
                load_slot_data();
        });
        
        function getUrlVars()
        {
            var vars = [], hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for(var i = 0; i < hashes.length; i++)
            {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }
            return vars;
        }

        setInterval(function(){
         	
            if($(".window_container .popover, .window_datepicker .popover").length ==0){
                if($(".btn-list-appointment.btn-selected").attr("id") == "live-tracker"){
                    $(".btn-list-appointment.btn-selected").trigger('click');
                }else if(tabType == "LIVE_TRACKER"){
                    getListByTabBtn(tabType);
                }else{
                    load_slot_data();
                }
            }

            if(smart_tracker=='YES'){
               $("#refresh_page_li").show();
            }
        }, 30000);





        loadingSlotTab =false;
        loadingSlot =false;
        function load_slot_data(){

            if($("#search_cus_modal").length == 0 && loadingSlot ==false && loadingSlotTab == false){

                if(tabType != "")
                {
                    getListByTabBtn(tabType);
                }
                else
                {

                    // refreshProduct();
                    var address_id = $('#address_drp').val();
                    var doctor_id = $('#doctor_drp').val();
                    var service_id = $('#service_drp').val();
                    var date =  $("#appointment_date").val();
                    obj =  $("#appointment_date");
                    $.ajax({
                        type:'POST',
                        url: baseurl+"app_admin/load_slot_data",
                        headers: {
                            'Cache-Control': 'max-age=1000'
                        },
                        data:{address_id:btoa(address_id),doctor_id:btoa(doctor_id),service_id:btoa(service_id),date:date},
                        beforeSend:function(){
                            loadingSlot =true;
                            loading(obj,true, "");
                        },
                        success:function(data){

                            loadingSlot =false;
                            $("#load_slot_div").html(data);
                            loading(obj,false);


                        },
                        error: function(data){
                            loadingSlot =false;
                            loading(obj,false);
                            $(".file_error").html("Sorry something went wrong on server.");
                        }
                    });

                }

            }
        }


        $.ajax({url: "<?php echo Router::url('/app_admin/create_data_list',true);?>"});


        $(document).on('click','#blog_slot_btn',function(e){

            var $btn = $(this);


            $.ajax({
                url: "<?php echo Router::url('/app_admin/block_slot_modal',true);?>",
                type:'POST',
                beforeSend:function(){
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                },
                success: function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#block_slot_modal');
                    $(html).modal('show');
                },error:function () {

                }
            });
        });


        function getListByTabBtn(type){
            loadingSlotTab =true;

            var address_id = $('#address_drp').val();
            var doctor_id = $('#doctor_drp').val();
            var service_id = $('#service_drp').val();
            var date =  $("#appointment_date").val();
            obj =  $("#appointment_date");
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/load_slot_data_by_type",
                data:{type:type,address_id:btoa(address_id),doctor_id:btoa(doctor_id),service_id:btoa(service_id),date:date},
                beforeSend:function(){
                    loading(obj,true, "");
                },
                success:function(data){
                    $("#load_slot_div").html(data);
                    loading(obj,false);
                },
                error: function(data){
                    loading(obj,false);
                    $(".file_error").html("Sorry something went wrong on server.");
                }
            });

        }

        $(document).on("click",".btn-list-appointment",function(){
           $(".btn-selected").removeClass("btn-selected");
            $(this).addClass("btn-selected").focus();
            var elementID = $(this).attr("id");
            $(this).removeClass('hover');

            if(elementID == "show-book-appointment"){
                loadingSlotTab =false;
                tabType = "";
                load_slot_data();
            }
            else if(elementID == "show-booked-appointment")
            {
                var type = "ALL_BOOKED";
                tabType = "ALL_BOOKED";
                getListByTabBtn(type);
            }
            else if(elementID == "show-cancel-appointment")
            {
                var type = "CANCEL";
                tabType = "CANCEL";
                getListByTabBtn(type);
            }
            else if(elementID == "show-close-appointment")
            {
                var type = "CLOSE";
                tabType = "CLOSE";
                getListByTabBtn(type);
            }
            else if(elementID == "show-checked-in-appointment")
            {
                var type = "CHECKED_IN";
                tabType = "CHECKED_IN";
                getListByTabBtn(type);
            }
            else if(elementID == "show-emergency-appointment")
            {
                var type = "EMERGENCY";
                tabType = "EMERGENCY";
                getListByTabBtn(type);
            }else if(elementID == "show-all-appointment")
            {
                var type = "ALL_APPOINTMENT";
                tabType = "ALL_APPOINTMENT";
                getListByTabBtn(type);
            }else if(elementID == "live-tracker")
            {
                var type = "LIVE_TRACKER";
                tabType = "LIVE_TRACKER";
                getListByTabBtn(type);
            }

        });

        $(document).on("click",".color_picker",function(e){
            <?php if($login['USER_ROLE'] != 'ADMIN'){ ?>
                e.preventDefault();
            <?php } ?>
        });

        $(document).on("change",".color_picker",function(e){


            <?php if($login['USER_ROLE'] == 'ADMIN'){ ?>
            var colorCode = $(this).val();
            var type = $(this).attr("data-value");
            obj =  $(this);
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/update_appointment_slot_color",
                data:{color_code:colorCode,type:type},
                beforeSend:function(){
                    loading(obj,true, "");
                },
                success:function(data){
                    loading(obj,false);
                    load_slot_data();
                },
                error: function(data){
                    loading(obj,false);
                    $(".file_error").html("Sorry something went wrong on server.");
                }
            });
            <?php }else{ ?>
            e.preventDefault();
            <?php } ?>

        });

        $(document).on('click','.history_btn',function(e){
            e.preventDefault();
            var url = $(this).attr('src');
            console.log(url);
            $("#ifram").attr("src",url);
            $("#patient_history_modal").modal('show');
        });









    });






</script>











