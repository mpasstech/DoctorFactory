<html>
<head id ="row_content" class="row_content" >
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">


    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>

    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','bootstrap4.min.js','popper.min.js','sweetalert2.min.js','jquery.maskedinput-1.2.2-co.min.js','jquery-confirm.min.js','moment.js', 'timepicker.min.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','sweetalert2.min.css','jquery-confirm.min.css','timepicker.min.css'),array("media"=>'all')); ?>


    <style>


        .jconfirm-content section{
            margin: 10px 0px;
            border-bottom: 1px solid #d8d8d8;
            padding: 10px 0px;
        }
        .wizard {
            margin: 2px auto;
            background: #fff;
        }

        .wizard .nav-tabs {
            position: relative;
            margin: 40px auto;
            margin-bottom: 0;
            border-bottom-color: #e0e0e0;
        }

        .wizard > div.wizard-inner {
            position: relative;
        }

        .connecting-line {
            height: 2px;
            background: #e0e0e0;
            position: absolute;
            width: 80%;
            margin: 0 auto;
            left: 0;
            right: 0;
            top: 50%;
            z-index: 1;
        }

        .wizard .nav-tabs > li.active > a, .wizard .nav-tabs > li.active > a:hover, .wizard .nav-tabs > li.active > a:focus {
            color: #555555;
            cursor: default;
            border: 0;
            border-bottom-color: transparent;
        }

        span.round-tab {
            width: 70px;
            height: 70px;
            line-height: 70px;
            display: inline-block;
            border-radius: 100px;
            background: #fff;
            border: 2px solid #e0e0e0;
            z-index: 2;
            position: absolute;
            left: 0;
            text-align: center;
            font-size: 25px;
        }
        span.round-tab i{
            color:#555555;
        }
        .wizard li.active span.round-tab {
            background: #fff;
            border: 2px solid #5bc0de;

        }
        .wizard li.active span.round-tab i{
            color: #5bc0de;
        }

        span.round-tab:hover {
            color: #333;
            border: 2px solid #333;
        }

        .wizard .nav-tabs > li {
            width: 25%;
        }

        .wizard li:after {
            content: " ";
            position: absolute;
            left: 46%;
            opacity: 0;
            margin: 0 auto;
            bottom: 0px;
            border: 5px solid transparent;
            border-bottom-color: #5bc0de;
            transition: 0.1s ease-in-out;
        }

        .wizard li.active:after {
            content: " ";
            position: absolute;
            left: 46%;
            opacity: 1;
            margin: 0 auto;
            bottom: 0px;
            border: 10px solid transparent;
            border-bottom-color: #5bc0de;
        }

        .wizard .nav-tabs > li a {
            width: 70px;
            height: 70px;
            margin: 20px auto;
            border-radius: 100%;
            padding: 0;
        }

        .wizard .nav-tabs > li a:hover {
            background: transparent;
        }

        .wizard .tab-pane {
            position: relative;
            padding-top: 10px;
        }

        .wizard h3 {
            margin-top: 0;
        }

        section{
            width: 100%;
        }


        .container {
            padding: 5% 3%;
        }
        @media( max-width : 585px ) {

            .wizard {
                width: 90%;
                height: auto !important;
            }

            span.round-tab {
                font-size: 16px;
                width: 50px;
                height: 50px;
                line-height: 50px;
            }

            .wizard .nav-tabs > li a {
                width: 50px;
                height: 50px;
                line-height: 50px;
            }

            .wizard li.active:after {
                content: " ";
                position: absolute;
                left: 35%;
            }
        }

        .button-box{
            width: 100%;
            display: block;
            float: left;
            margin-top: 20px;
        }

        .button-box li{
            float: right;
            width: auto;
            margin: 2px;
            list-style: none;
        }

        .tab-pane .col-12{
            height: 96px;
        }
        .error_lbl{
            font-size: 0.8rem;
            color: red;
        }

        .table td, .table th {
            padding: .3rem .3rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
            font-size: 0.9rem;
        }

        .table .form-control{
            padding: 0.3rem 0.2rem !important;
            font-size: 0.9rem;
            height: auto !important;
        }

        #otp_div{
            border-top: 2px solid #73dc80;
            padding-top: 7px;
        }
        .form_title{
            color: #3478de;
            text-align: center;
            font-size: 1.2rem;

        }
        .top_header{
            text-align: center;
            border-bottom: 1px solid;
        }
        .selector{
            padding: 5px;
            border: 1px solid #ced4da;
        }


        .inner_box{
            float: left;
            width: 48%;
            margin: 0px 2px;
        }

        .time_box {
            position: relative;
            z-index: 1000;
            float: left;
            width: 100%;
            padding-bottom: 5px;
            margin: 2px 0 0 0;
            list-style: none;
            font-size: 14px;
            text-align: center;
            background-color: #fff;
            border: 1px solid #ccc;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            background-clip: padding-box;
        }


        .edit_info_btn{
            float: right;
            background: none;
        }

        .no_padding{
            padding: 0px !important;
        }

        .center_btn{
            text-align: center;
            width: 100%;
        }

        .entity_type_fees{
            display: none;
        }
        .time_lbl_span{
            display: block;
            float: left;
            font-size: 0.65rem;
            color: green;
            margin: 7px 0px;
        }
        .button_pre_p{
            display: block;
            width: 100%;
            /*border-top: 1px solid #d4d4d4;*/
            margin-top: 0px;
            text-align: center;
        }
        .p_last_btn{
            display: block;
            width: 100%;
            margin-top: 10px;
            text-align: center;
            float: left;
            border-top: 1px solid;
        }

        .show_content{
            float: left;

        }

        .float_right{
            float: right;
        }



    </style>

</head>
<body>

<div class="container">
    <div class="row">

            <div class="col-12 top_header"  style="text-align: center;">
                <img src="<?php echo Router::url('/home/images/logo.png',true); ?>" />
                <h4>QuToT Signup Form</h4>

            </div>
    </div>

    <div class="row">

        <section>
            <div class="wizard">
                <form role="form" method="post" id="data_form">
                    <div class="tab-content">
                        <div class="tab-pane active" role="tabpanel" >
                            <h5 class="form_title">Basic Information</h5>
                            <div class="col-12">
                            <label>Entity Type</label>

                                <select class="form-control" id="entity_type" name="entity_type">
                                    <?php
                                        $category[] = 'Hospitals';
                                        $category[] = 'Doctor OPDs';
                                        $category[] = 'Billing Counters';
                                        $category[] = 'Saloons';
                                        $category[] = 'Banks';
                                        $category[] = 'Vaccination Camps';
                                        $category[] = 'Temple Queues';
                                        $category[] = 'Ticket Bookings';
                                        $category[] = 'Cash Withdrawals';
                                        $category[] = 'Delivery Counters';
                                        $category[] = 'Enquiry Counters';
                                        $category[] = 'Restaurant Queues';
                                        $category[] = 'Tourist Sites';
                                        $category[] = 'Job Application Counters';
                                        $category[] = 'Other';
                                        foreach ($category as $key =>$cat){
                                    ?>
                                        <option value="<?php echo strtoupper(strtolower(str_replace(' ','_', $cat))); ?>"><?php echo $cat; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label>Name of Entity</label>
                                <input type="text" autocomplete="off" name="entity_name"  data-type="app_name" class="form-control validate_box">
                            </div>

                            <div class="col-12">
                                <label>Mobile Number</label>
                                <input type="number"  id="mobile_number" autocomplete="off" maxlength="10" name="mobile_number" class="form-control validate_box" data-type="mobile">
                            </div>

                            <div class="col-12" id="otp_div" style="display: none;">
                                <label style="font-weight: 600;">Enter OTP</label>
                                <input type="number"  autocomplete="off" name="OTP" id="otp" class="form-control">
                            </div>

                            <div class="button-box">
                                <li><button type="button" style="display: none;" id="first_next" class="btn btn-primary next-step">Save & Next</button></li>
                                <li><button type="button" id="send_otp"  class="btn btn-primary">Verify</button></li>
                            </div>
                        </div>
                        <div class="tab-pane" role="tabpanel" >
                            <h5 class="form_title">Other Information</h5>


                            <div class="col-12">
                                <label>Address</label>
                                <input type="text"  autocomplete="off" name="address" class="form-control validate_box" data-type="not_empty">
                            </div>
                            <div class="col-12">
                                <label>E-mail</label>
                                <input type="email" autocomplete="off" data-type="email" name="email"  class="form-control validate_box">
                            </div>
                            <div class="button-box">
                                <li><button type="button"  class="btn btn-primary next-step">Save & Next</button></li>
                                <li><button type="button"  class="btn btn-default prev-step">Back</button></li>
                            </div>
                        </div>


                        <div class="tab-pane counter_executive_div" role="tabpanel" >
                            <h5 class="form_title">Counter/Executive </h5>
                            <div class="col-12">
                            <label>Counter/Executive Name</label>
                            <input type="text" autocomplete="off" data-type="not_empty" name="executive[counter_name][]"  class="form-control counter_name validate_box">
                            </div>
                            <div class="col-12">
                                <label>Mobile Number</label>
                                <input type="number"  autocomplete="off" maxlength="10" name="executive[counter_number][]"  class="form-control counter_number validate_box" data-type="mobile">
                            </div>
                            <div class="button-box">
                                <li><button type="button"  class="btn btn-primary next-step">Save & Next</button></li>
                                <li style="display: none;"><button type="button" data-div="counter_executive_div" class="btn btn-success add_more">Add More</button></li>
                                <li><button type="button"  class="btn btn-default prev-step">Back</button></li>
                            </div>
                        </div>

                        <!-- assistent div is commented-->
                        <!--<div class="tab-pane-hide counter_assistant_div" style="display: none;" role="tabpanel" >
                            <h5 class="form_title">Counter Assistant (<span class='sr_number'>1</span>)</h5>
                            <div class="col-12 append_executive_div">
                            </div>
                            <div class="col-12">
                                <label>Assistant Name</label>
                                <input type="text" autocomplete="off" data-type="not_empty" name="assistant[assistant_name][]"  class="form-control validate_box">
                            </div>
                            <div class="col-12">
                                <label class="main_mobile">Mobile Number</label>
                                <input type="number"  autocomplete="off" maxlength="10" name="assistant[assistant_number][]"  class="form-control validate_box" data-type="mobile">
                            </div>
                            <div class="button-box">
                                <li><button type="button"  class="btn btn-primary next-step">Save & Next</button></li>
                                <li><button type="button"  class="btn btn-warning skip">Skip</button></li>
                                <li style="display: none;"><button type="button" data-div="counter_assistant_div" class="btn btn-success add_more">Add More</button></li>
                                <li><button type="button"  class="btn btn-default prev-step">Back</button></li>
                            </div>
                        </div>-->


                        <div class="tab-pane" role="tabpanel" >
                            <h5 class="form_title">Type of service</h5>

                            <div class="col-12 nature_of_service">
                                <label>Nature of service</label>
                                <select class="form-control" id="service_type">
                                    <?php
                                    foreach ($category as $key =>$cat){
                                        ?>
                                        <option value="<?php echo strtoupper(strtolower(str_replace(' ','_', $cat))); ?>"><?php echo $cat; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12" id="service_name_div" style="display: none;">
                                <label>Service Name</label>
                                <input type="text"  autocomplete="off" data-type="not_empty" name="service[name]" id="service_name" class="form-control validate_box">
                            </div>

                            <div class="col-12" style="height: 110px;">
                                <label style="display:block;">Slot duration</label>
                                <span class="time_lbl_span" >( Please fill average time spend in handling a user at the counter)</span>
                                <select class="form-control" id="service_duration" name="service[service_duration]">
                                    <?php
                                    for($minute=1;$minute<=60;$minute++){
                                        $min_label = ($minute < 2)?'MINUTE':'MINUTES';
                                        $dis_min = ucfirst(strtolower($min_label));

                                        ?>
                                        <option value="<?php echo $minute.' '.$min_label ; ?>"><?php echo $minute.' '.$dis_min; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12" id="time_box">
                                <label style="display: block;">Service Timing</label>
                                <div class="inner_box">
                                    <label style="width: 100%;display: block;">From Time</label>
                                    <input type="text" value="10:00"  autocomplete="off" data-type="not_empty" name="service[time_from]" class="form-control time_box validate_box">
                                </div>
                                <div class="inner_box">
                                    <label style="width: 100%;display: block;">To Time</label>
                                    <input type="text"  value="18:00" autocomplete="off" data-type="not_empty" name="service[time_to]" class="form-control time_box validate_box">
                                </div>
                            </div>

                            <br>
                            <div class="button-box">
                                <li><button type="button"  class="btn btn-primary next-step">Save & Next</button></li>
                                <li><button type="button"  class="btn btn-default prev-step">Back</button></li>
                            </div>
                        </div>
                        <div class="tab-pane service_charges_screen"  role="tabpanel" >
                            <h5 class="form_title">Service Charges</h5>
                            <p>What type of service provide by you</p>
                            <div class="col-12">
                                <label>Clinic/Visit Consultation Fee</label>
                                <input type="number" autocomplete="off" name="service[offline]" id="offline_fee" class="form-control">
                            </div>
                            <div class="col-12 entity_type_fees">
                                <label>Video Consultation Fee</label>
                                <input type="number" autocomplete="off" name="service[video]" id="video_fee" class="form-control">
                            </div>
                            <div class="col-12 entity_type_fees" >
                                <label>Audio Consultation Fee</label>
                                <input type="number" autocomplete="off" name="service[audio]" id="audio_fee" class="form-control">
                            </div>
                            <div class="col-12 entity_type_fees">
                                <label>Chat Consultation Fee</label>
                                <input type="number"  autocomplete="off" name="service[chat]" id="chat_fee" class="form-control">
                            </div>
                            <div class="button-box">
                                <li><button type="button" class="btn btn-info btn-info-full next-step">Save & Next</button></li>
                                <li><button type="button"  class="btn btn-default prev-step">Back</button></li>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>
        </section>

    </div>
</div>


<script>
    $(document).ready(function () {
        var baseurl = "<?php echo Router::url('/',true); ?>";

        //Initialize tooltips
        $('.nav-tabs > li a[title]').tooltip();
        //Wizard
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            var $target = $(e.target);
            if ($target.parent().hasClass('disabled')) {
                return false;
            }
        });


        $('.time_box').timepicker();

        $("#service_duration").val('3 MINUTES');

        $(document).on("change","#service_type",function (e) {
            $("#service_name").val('');
            $("#service_name_div").hide();
            var val = $(this).val();
            if(val=='OTHER'){
                $("#service_name_div").show();
            }else{
                $("#service_name").val($("#service_type option:selected").text());
            }
        });

        $("#service_type").trigger('change');


        $(document).on("click",".next-step, .skip",function (e) {
            showScreen(this);
        });


         $(document).on("change","#entity_type",function (e) {
            $(".entity_type_fees").hide();
            var val = $(this).val();
            if(val=='HOSPITALS' || val=='DOCTOR_OPDS'){
                $(".entity_type_fees").show();
            }
             $("#service_type").val(val);

             $("#service_type").trigger('change');
        });

         $("#entity_type").trigger("change");




        function closePreviewDialog(obj){
            if(prev_dialog){
                prev_dialog.close();
            }
            showScreen(obj);
        }





        function showScreen(click_object){
            var allow_next = true;
            if($(click_object).is('.next-step')){
                $(click_object).closest('.tab-pane').find('.validate_box').each(function(){
                    if(!formValidation(this)){
                        allow_next = false;
                    }
                });
            }
            if(allow_next){

                var obj = $(click_object).closest('.tab-pane').next('.tab-pane');
                if(obj.length==0){
                    openFinalScreen();
                    return false;
                }else{
                    if($(obj).hasClass('service_charges_screen')){
                        var val = $("#entity_type").val();
                        if(val!='HOSPITALS' && val!='DOCTOR_OPDS'){
                            openFinalScreen();
                            return false;
                        }
                    }
                    $('.tab-pane').removeClass('active');
                    $('.tab-pane').hide();
                    var option = "";
                    if($(obj).hasClass('counter_assistant_div')){
                        $(".counter_name").each(function () {
                            var name = $(this).closest('.tab-pane').find('.counter_name').val();
                            var mobile = $(this).closest('.tab-pane').find('.counter_number').val();
                            option += "<option value="+mobile+">"+name+"</option>";
                        })
                        var htm = "<label>Associate with </label><select class='form-control' name='assistant[associate_with][]'>"+option+"</select>";
                        $(obj).find(".append_executive_div").html(htm);
                    }
                    $(obj).addClass('active');
                    $(obj).show();
                }



            }
        }


        $(document).on("click",".prev-step",function (e) {
            $('.tab-pane').removeClass('active');
            $('.tab-pane').hide();
            var obj = $(this).closest('.tab-pane').prev('.tab-pane');
            $(obj).addClass('active');
            $(obj).show();
        });


        $(document).on("click",".add_more",function (e) {

            var allow_next = true;
            $(this).closest('.tab-pane').find('.validate_box').each(function(){
                if(!formValidation(this)){
                    allow_next = false;
                }
            });


            if(allow_next){
                $(this).hide();
                $('.tab-pane').removeClass('active');
                $('.tab-pane').hide();
                var cloneClass = $(this).attr('data-div');
                cloneClass = '<div class="tab-pane">'+ $("."+cloneClass+":first").html()+"</div>";
                var obj = $(this).closest('.tab-pane').next('.tab-pane');
                obj = $(cloneClass).insertBefore(obj);
                $(obj).find('input').val('');
                $(obj).find('.add_more').show();
                $(obj).find('.sr_number').html(parseInt($(obj).find('.sr_number').text())+1);

                $(obj).find('.add_more').addClass('delete_div').removeClass('add_more');
                $(obj).find('.skip').remove();
                $(obj).find('.delete_div').addClass('btn-danger').removeClass('btn-success');
                $(obj).find('.delete_div').html('Delete');
                $(obj).addClass('active');
                $(obj).show();



            }

        });




        $(document).on("click",".delete_div",function (e) {
            var current = $(this).closest('.tab-pane');
            $('.tab-pane').removeClass('active');
            $('.tab-pane').hide();
            var obj = $(this).closest('.tab-pane').prev('.tab-pane');
            $(obj).find('.add_more').show();
            $(obj).addClass('active');
            $(obj).show();
            $(current).remove();
        });




        $(document).on('input','.validate_box',function(e){
            formValidation(this);
        });

        function formValidation(obj){

            //return  true;
            var value_str =$(obj).val();
            var object =$(obj).closest('.col-12');
            $(object).find('.error_lbl').remove();
            var type =$(obj).attr('data-type');
            var message = '';
            if(type=='mobile'){
                if(value_str.length != 10 || !value_str.match('[1-9]{1}[0-9]{9}'))  {
                    message = 'Enter 10 digit valid mobile number';
                }
            }else if(type=='app_name'){
                var letters = /^[A-Za-z\s]+$/;
                if(value_str==''){
                    message = 'Please enter Clinic/Hospital name';
                }else if(!value_str.match(letters)){
                    message = 'Enter only alphabets';
                }
            }else if(type=='not_empty'){
                if(value_str=='')  {
                    message = 'This field cannot be empty';
                }
            }else if(type=='email'){
                if(value_str != ''){
                    var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
                    if(!pattern.test(value_str)){
                        message='Please enter valid email address';
                    }
                }
            }
            if(message!=''){
                $(object).append("<label class='error_lbl'>"+message+"</label>");
                return false;
            }
            return true;
        }

        var resendInterval;
        var counter;
        var $send_otp_btn = $("#send_otp");
        var otp_btn_text = $($send_otp_btn).text();
        $(document).on('click','#send_otp',function(e){

            var allow_next = true;
            $(this).closest('.tab-pane').find('.validate_box').each(function(){
                if(!formValidation(this)){
                    allow_next = false;
                }
            });
            if(allow_next){


                $.ajax({
                    type:'POST',
                    url: baseurl+"homes/send_otp",
                    data:{m:$("#mobile_number").val()},
                    beforeSend:function(){
                        if(resendInterval){
                            clearInterval(resendInterval);
                        }
                        $send_otp_btn.text('Sending OTP');
                        $send_otp_btn.attr('disabled',true);
                        counter = 30;
                    },
                    success:function(response){
                        $send_otp_btn.text('Resend OTP');
                        resendInterval =  setInterval(function () {
                            if(counter > 0){
                                $send_otp_btn.text('Resend OTP ( '+  (counter--) +' )');
                            }else{
                                $send_otp_btn.text('Resend OTP');
                                $send_otp_btn.attr('disabled',false);
                            }
                        },1000);
                        if(response){
                            $("#otp_div").slideDown('500');
                            $("#otp_div").focus();
                            $("#otp_div").find('input').attr('data-t',response);
                        }

                    },
                    error: function(data){
                        $btn.text(otp_btn_text);
                        $btn.attr('disabled',false);
                    }
                });
            }
        });


        $(document).on('input','#otp',function(e){

            if(btoa($("#otp").val())==$("#otp").attr('data-t')){
                $("#send_otp").html('Next');
                $("#send_otp").attr('disabled',false);
                $("#first_next").trigger('click');
                if(resendInterval){
                    clearInterval(resendInterval);
                }
                $send_otp_btn.text(otp_btn_text);
                $send_otp_btn.hide();
                $("#mobile_number").attr('readonly','readonly');
                $(this).attr('disabled','disabled');
                $("#first_next").show();
            }else{
                $("#first_next").hide();
                //$send_otp_btn.attr('disabled',false);
                $send_otp_btn.show();
                //alert('Invalid OTP');
            }
        });

        $(document).on('click','.edit_info_btn',function(e){
            var id = $(this).attr('data-id')+"";
            $.each(object_array,function (index,value) {
                if(value.id== id){
                    var data_object = value.obj;
                    var label = value.label;
                    var value = $(data_object).val();
                    var css_class = $(data_object).attr('class');
                    var data_type = $(data_object).attr('data-type');

                    var type = $(data_object).attr('type');
                    var content = '';
                    if(data_object.is('input')){
                        content = "<div class='col-12'> <label style='display:block;'>"+label+"</label><input class='"+css_class+"' data-type='"+data_type+"' type='"+type+"' id='update_input_box' value='"+value+"'></div>";
                    }else{
                        content = "<div class='col-12 confirm_box_div' > <label style='display:block;'>"+label+"</label></div>";
                    }

                    var update_dialog = $.confirm({
                        title: 'Edit',
                        content:content,
                        type: 'red',
                        buttons: {
                            ok: {
                                text: "Update",
                                btnClass: 'btn-primary confirm_btn',
                                keys: ['enter'],
                                name:"ok",
                                action: function(e){
                                    var $btn = $(".confirm_btn");
                                    var last = $(".confirm_btn").text();
                                    var update_value = this.$content.find("#update_input_box").val();
                                    $("."+id).html(update_value);
                                    $(data_object).val(update_value);
                                    if(formValidation(this.$content.find("#update_input_box"))){

                                        var option = "";
                                        if($(data_object).hasClass('counter_name')){
                                            $(".counter_executive_div .counter_name").each(function () {
                                                var name = $(this).closest('.tab-pane').find('.counter_name').val();
                                                var mobile = $(this).closest('.tab-pane').find('.counter_number').val();
                                                option += "<option value="+mobile+">"+name+"</option>";
                                            })
                                            var htm = "<label>Associate with </label><select class='form-control' name='assistant[associate_with][]'>"+option+"</select>";
                                            $(".append_executive_div").html(htm);


                                        }
                                        update_dialog.close();
                                        openFinalScreen();
                                    }else{
                                        return false;
                                    }
                                }
                            },
                            cancel: function(){

                            },

                        },
                        onContentReady:function(){
                            if(!data_object.is('input')){
                                $(data_object).clone().appendTo(this.$content.find(".confirm_box_div"));
                                this.$content.find("select").val(value);


                            }
                        }
                    });

                    return false;
                }

            })



        });

        var prev_dialog;
        var object_array=[];
        function openFinalScreen(){
            if(btoa($("#otp").val())==$("#otp").attr('data-t')){
                var prv_string = '';
                object_array = [];
                var label,value;
                var entity_type = $("#entity_type").val();
                $('.tab-pane').each(function (index,value) {
                    var title = $(this).find('.form_title').text();
                    var allow_add_string = true;
                    if($(this).hasClass('nature_of_service')){
                        allow_add_string=false;
                    }else if($(this).attr('id')=='otp_div'){
                        allow_add_string=false;
                    }else if($(this).hasClass('service_charges_screen')){
                        if(entity_type!='HOSPITALS' && entity_type!='DOCTOR_OPDS'){
                            allow_add_string=false;
                        }
                    }
                    if(allow_add_string){
                        prv_string += "<section><h5 style='color:#2a65e8;'>"+title+"</h5><table style='width:100%;'>";
                        $(this).find('.col-12').each(function () {

                            var rand = Math.random().toString(16).slice(2);
                            var id = (new Date()).getTime()+'_'+rand;
                            if($(this).attr('id')!='time_box'){
                                if($(this).attr('id')!='otp_div'){
                                     label = $(this).find('label').text();
                                     value = $(this).find('input').val();
                                    if(!value){
                                        value = $(this).find('select option:selected').text();
                                    }
                                    var json_object = {'id':id,'obj':$(this).find('input,select'),'label':label};
                                    object_array.push(json_object);
                                    var btn = "<button type='button' data-index='"+index+"' class='btn edit_info_btn' data-id='"+id+"'><i class='fa fa-pencil'></i></button>";
                                    if($(this).find('label').hasClass('main_mobile')){
                                        btn ='';
                                    }
                                    prv_string += "<tr><td style='font-weight:500;'>"+label+"</td><td class='"+id+"'>"+value+"</td><td>"+btn+"</td></tr>";
                                }
                            }else{
                                $(this).find('.inner_box').each(function () {
                                    label = $(this).find('label').text();
                                    value = $(this).find('input,select').val();
                                    var json_object = {'id':id,'obj':$(this).find('input,select'),'label':label};
                                    object_array.push(json_object);
                                    var btn = "<button type='button' data-index='"+index+"' class='btn edit_info_btn' data-id='"+id+"'><i class='fa fa-pencil'></i></button>";
                                    prv_string += "<tr><td style='font-weight:500;'>"+label+"</td><td class='"+id+"'>"+value+"</td><td>"+btn+"</td></tr>";
                                });
                            }

                        });
                        prv_string += "</table></section>";
                    }
                });
                if(prev_dialog){
                    prev_dialog.close();
                    prev_dialog=false;
                }
                prev_dialog = $.confirm({
                    title: 'App Preview',
                    content:prv_string+"<h5 class='button_pre_p'> Preview Interface</h5>",
                    type: 'Green',
                    backgroundDismissAnimation:'',
                    buttons: {
                        pei: {
                            text: "Executive",
                            btnClass: 'btn-success pei',
                            keys: ['enter'],
                            name:"pei",
                            action: function(e){
                                loadPrevHtml('pei','EXECUTIVE');
                                return false;
                            }
                        },
                       /* pai: {
                            text: "Assistant",
                            btnClass: 'btn-success pai',

                            keys: ['enter'],
                            name:"pai",
                            action: function(e){
                                loadPrevHtml('pai','ASSISTANT');
                                return false;

                            }
                        },*/
                        pwui: {
                            text: "Walk-in User",
                            btnClass: 'btn-success pwui',

                            keys: ['enter'],
                            name:"pwui",
                            action: function(e){
                                loadPrevHtml('pwui','WALK-IN');
                             return false;

                            }
                        },
                        pouwa: {
                            text: "Online User Web App",
                            btnClass: 'btn-success pouwa pouwa_btn',
                            keys: ['enter'],
                            name:"pouwa",
                            action: function(e){
                                loadPrevHtml('pouwa','WEB_APP');
                                return false;

                            }
                        },
                        ok: {
                            text: "Create App",
                            btnClass: 'btn-primary confirm_btn float_right',
                            keys: ['enter'],
                            name:"ok",
                            action: function(e){
                                var $btn2 = $(".confirm_btn_cancel");
                                var $btn = $(".confirm_btn");
                                var last = $(".confirm_btn").text();
                                $.ajax({
                                    type:'POST',
                                    url: baseurl+"homes/save_web_app",
                                    data:$("#data_form").serialize(),
                                    beforeSend:function(){
                                        $btn.text('Please wait...');
                                        $btn.attr('disabled',true);
                                    },
                                    success:function(response){
                                        $btn.text(last);
                                        $btn.attr('disabled',false);
                                        response = JSON.parse(response);
                                        if (response.status == 1) {
                                            var t = btoa(response.thin_app_id);
                                            var url = baseurl+'homes/app_created/'+t;
                                            window.location.href = url;
                                        }else{
                                            $btn.text(last);
                                            $btn.attr('disabled',false);
                                            alert(response.message);
                                        }
                                    },
                                    error: function(data){
                                        $btn.text(last);
                                        $btn.attr('disabled',false);
                                    }
                                });

                                return false;
                            }
                        },
                        back: {
                            text: "<i class='fa fa-arrow-left'></i>  Back",
                            btnClass: 'btn-default float_right',
                            keys: ['enter'],
                            name:"back",
                            action:function(e) {
                                prev_dialog.close();
                            }
                        }
                    },
                    onContentReady:function(){

                        $( '<p class="p_last_btn"></p>' ).insertAfter( this.$jconfirmBoxContainer.find(".pouwa_btn") );
                    }
                });
            }else{
                alert('Invalid OTP');
            }
        }


        function loadPrevHtml(className, flag){

            var $btn = $("."+className);
            var last = $("."+className).text();
            $.ajax({
                type:'POST',
                url: baseurl+"homes/mq_form_preview/"+flag,
                data:$("#data_form").serialize(),
                beforeSend:function(){
                    $btn.text('Creating...');
                    $btn.attr('disabled',true);
                },
                success:function(data){
                    $btn.text(last);
                    $btn.attr('disabled',false);
                    var inner_prev_dialog = $.confirm({
                        title: '',
                        content:data,
                        type: 'green',
                        lazyOpen: false,
                        smoothContent:false,
                        buttons: {
                            ok: {
                                text: "<i class='fa fa-arrow-left'></i> Back",
                                btnClass: 'btn-primary confirm_btn',
                                keys: ['enter'],
                                name:"ok",
                                columnClass:'preview_box',
                                action: function(e){
                                    inner_prev_dialog.close();
                                }
                            }
                        },
                        onOpen:function(){
                            this.$jconfirmBoxContainer.find(".jconfirm-box").addClass("no_padding");
                            this.$jconfirmBoxContainer.find(".jconfirm-buttons").addClass("center_btn");
                            this.$content.addClass("show_content");



                        }
                    });
                },
                error: function(data){
                    $btn.text(last);
                    $btn.attr('disabled',false);
                }
            });

        }



    });


</script>
</body>

</html>


