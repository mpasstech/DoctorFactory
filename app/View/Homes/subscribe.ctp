<html>
<head id ="row_content" class="row_content" >
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">


    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>

    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','bootstrap4.min.js','popper.min.js','sweetalert2.min.js','jquery.maskedinput-1.2.2-co.min.js','jquery-confirm.min.js','moment.js', 'bootstrap-datetimepicker.min.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','sweetalert2.min.css','jquery-confirm.min.css','bootstrap-datepicker.min.css'),array("media"=>'all')); ?>


    <style>
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
        }

        .button-box li{
            float: right;
            width: auto;
            margin: 5px;
            list-style: none;
        }

        .tab-pane .col-sm-12{
            height: 90px;
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
    </style>

</head>
<body>

<div class="container">
    <div class="row">

            <div class="col-12 top_header"  style="text-align: center;">
                <img src="<?php echo Router::url('/home/images/logo.png',true); ?>" />
                <h4>Web App Creation Form</h4>
                <label style="font-size: 0.9rem;">Please enter basic information for create your personal telemedicine web app</label>
            </div>
    </div>

    <div class="row">

        <section>
            <div class="wizard">
                <form role="form" method="post" id="data_form">
                    <div class="tab-content">
                        <div class="tab-pane active" role="tabpanel" id="step1">
                            <h5 class="form_title">Basic Information</h5>
                            <div class="col-sm-12">
                            <label>Clinic/Hospital Name</label>
                            <input type="text" autocomplete="off" name="clinic_name" id="clinic_name" data-type="app_name" class="form-control validate_box">
                            </div>
                                <div class="col-sm-12">
                            <label>Doctor Name</label>
                            <input type="text" autocomplete="off" name="doctor_name" id="doctor_name" data-type="doctor_name" class="form-control validate_box">
                                </div>
                                    <div class="col-sm-12">
                            <label>Mobile Number</label>
                            <input type="number"  autocomplete="off" maxlength="10" name="mobile_number" id="mobile_number" class="form-control validate_box" data-type="mobile">
                                    </div>
                                        <div class="col-sm-12">

                            <?php $cat_list = $this->AppAdmin->getDepartmentCategoryDropdown(); ?>

                            <label>Category</label>
                            <?php $type_array = $this->AppAdmin->getDepartmentCategoryDropdown(); ?>
                            <?php echo $this->Form->input('department_category_id',array('required'=>'required','oninvalid'=>"this.setCustomValidity('Please select category.')",'oninput'=>"setCustomValidity('')" ,'empty'=>'Select Category','type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control message_type cnt')); ?>
                                        </div>
                                            <div class="col-sm-12">
                            <label>E-mail</label>
                            <input type="email" autocomplete="off" data-type="email" name="email" id="email" class="form-control validate_box">
                                            </div>
                            <br>
                            <div class="button-box">
                                <li><button type="button" id="first_step_btn" data-control="step2" class="btn btn-primary next-step">Save and Next</button></li>
                            </div>

                        </div>
                        <div class="tab-pane" role="tabpanel" id="step2">
                            <h5 class="form_title">Address</h5>
                            <div class="col-sm-12">
                            <label>Clinic/Hospital Address</label>
                            <input type="text" autocomplete="off" data-type="address" name="address" id="address" class="form-control validate_box">

                            </div>
                            <div class="col-sm-12">
                                <label>Country</label>
                                <?php
                                $country_list =$this->AppAdmin->countryDropdown();
                                echo $this->Form->input('country_id',array('oninvalid'=>"this.setCustomValidity('Please select country.')",'oninput'=>"setCustomValidity('')" ,'type'=>'select','label'=>false,'empty'=>array('0'=>'Select Country'),'options'=>$country_list,'class'=>'form-control country')); ?>
                            </div>
                            <div class="col-sm-12">
                                <?php $state_list =@$this->AppAdmin->stateDropdown(@$this->request->data['AppointmentStaff']['country_id']);?>
                                <label>State <i class="fa fa-spinner fa-spin state_spin" style="display:none;"></i> </label>
                                <?php echo $this->Form->input('state_id',array('oninvalid'=>"this.setCustomValidity('Please select state.')",'oninput'=>"setCustomValidity('')" ,'type'=>'select','label'=>false,'empty'=>array('0'=>'Select State'),'options'=>$state_list,'class'=>'form-control state')); ?>
                            </div>


                            <div class="col-sm-12">
                                <?php $city_list =@$this->AppAdmin->cityDropdown(@$this->request->data['AppointmentStaff']['state_id']);?>
                                <label>City <i class="fa fa-spinner fa-spin city_spin" style="display:none;"></i></label>
                                <?php echo $this->Form->input('city_id',array('oninvalid'=>"this.setCustomValidity('Please select city.')",'oninput'=>"setCustomValidity('')" ,'type'=>'select','label'=>false,'empty'=>array('0'=>'Select City'),'options'=>$city_list,'class'=>'form-control city')); ?>
                            </div>

                            <br>
                            <div class="button-box">
                                <li><button type="button" data-control="step3" class="btn btn-primary next-step">Save and continue</button></li>
                                <li><button type="button" data-control="step1" class="btn btn-default prev-step">Previous</button></li>
                            </div>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="step3">
                            <h5 class="form_title">Select Day Setting</h5>
                            <p>Select which day you consult with patients</p>
                            <div class="list-inline">
                                <div class="table-responsive">
                                        <form method="post">
                                            <table id="hours_table" class="table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th style="width: 14%;">Day</th>
                                                    <th style="width: 28%;">Time From</th>
                                                    <th style="width: 28%;">Time To</th>
                                                    <th style="width: 30%;">Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($hours_list as $key => $list){ ?>
                                                    <tr>
                                                        <?php
                                                        $dowMap = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday','Sunday');
                                                        $day = $dowMap[$list['number']-1];

                                                        $status =  $list['status'];
                                                        ?>
                                                        <td><?php echo $key+1; ?></td>
                                                        <td><?php echo $day; ?>  </td>
                                                        <td><input name="day[from_time][<?php echo $number; ?>]" onkeydown="return false" class="form-control time_box" type="text" value="<?php echo $list['time_from']; ?>" ></td>
                                                        <td><input name="day[to_time][<?php echo $number; ?>]" onkeydown="return false" class="form-control time_box" type="text" value="<?php echo $list['time_to']; ?>" ></td>
                                                        <td>
                                                            <select class="form-control" name="day[status][<?php echo $number; ?>]" >
                                                                <option value="OPEN" <?php echo ($status=='OPEN')?'selected':''; ?> >OPEN</option>
                                                                <option value="CLOSED" <?php echo ($status=='CLOSED')?'selected':''; ?>>CLOSED</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                <?php } ?>


                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                <div class="button-box">
                                    <li><button type="button" data-control="step4" class="btn btn-primary btn-info-full next-step">Save and continue</button></li>
                                    <li><button type="button" data-control="step2" class="btn btn-default prev-step">Previous</button></li>

                                </div>

                            </div>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="step4">
                            <h5 class="form_title">Service Detail</h5>
                            <p>What type of service provide by you</p>

                            <div class="col-sm-12">
                                <label>Service Name</label>
                                <input type="text"  autocomplete="off" data-type="service_name" name="service[name]" id="service_name" class="form-control validate_box">
                            </div>

                            <div class="col-sm-12">
                                <label>Clinic/Visit Consultation Fee</label>
                                <input type="number" autocomplete="off" name="service[offline]" id="offline_fee" class="form-control">
                            </div>


                            <div class="col-sm-12">
                                <label>Video Consultation Fee</label>
                                <input type="number" autocomplete="off" name="service[video]" id="video_fee" class="form-control">
                            </div>

                            <div class="col-sm-12">
                                <label>Audio Consultation Fee</label>
                                <input type="number" autocomplete="off" name="service[audio]" id="audio_fee" class="form-control">
                            </div>

                            <div class="col-sm-12">
                                <label>Chat Consultation Fee</label>
                                <input type="number"  autocomplete="off" name="service[chat]" id="chat_fee" class="form-control">
                            </div>

                            <div class="col-sm-12" id="otp_div" style="display: none;">
                                <label style="font-weight: 600;">Enter OTP</label>
                                <input type="number"  autocomplete="off" name="OTP" id="otp" class="form-control">
                            </div>


                            <br>
                            <div class="button-box">
                                <li><button type="button" data-button="last" id="send_otp" class="btn btn-primary btn-info-full">Send OTP</button></li>
                                <li><button type="button" style="display:none;"  id="finish_btn" class="btn btn-success btn-info-full">Create App</button></li>
                                <li><button type="button" data-control="step3" class="btn btn-default prev-step">Previous</button></li>
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


        $('.time_box').datetimepicker({
            format: 'hh:mm A'
        });


        $(".next-step, .prev-step").click(function (e) {
            var allow_next = true;
            if($(this).is('.next-step')){
                $(this).closest('.tab-pane').find('.validate_box').each(function(){
                    if(!formValidation(this)){
                        allow_next = false;
                    }
                });
            }
            if(allow_next){
                var control = $(this).attr('data-control');
                var current = $(this).closest('.tab-pane').attr('id');
                $("#"+current).hide();
                $("#"+control).show();
            }

        });


        $(document).on('change','.country',function(e){
            var country_id =$(this).val();
            if(country_id){
                $.ajax({
                    type:'POST',
                    url: baseurl+"homes/get_state_list",
                    data:{country_id:country_id},
                    beforeSend:function(){
                        $('.state_spin').show();
                        $('.city, .state').attr('disabled',true).html('');
                    },
                    success:function(data){
                        $('.state_spin').hide();
                        $('.state').html(data);
                        $('.city, .state').attr('disabled',false);
                        // $(".state").trigger('change');
                    },
                    error: function(data){
                    }
                });
            }

        });


        $(document).on('change','.state',function(e){
            var state_id =$(this).val();
            if(state_id){
                $.ajax({
                    type:'POST',
                    url: baseurl+"homes/get_city_list",
                    data:{state_id:state_id},
                    beforeSend:function(){
                        $('.city_spin').show();
                        $('.city').attr('disabled',true).html('');
                    },
                    success:function(data){
                        $('.city_spin').hide();
                        $('.city').attr('disabled',false).html(data);
                    },
                    error: function(data){
                    }
                });
            }

        });


        $(document).on('input','.validate_box',function(e){
            formValidation(this);
        });

        function formValidation(obj){

            //return  true;
            var value_str =$(obj).val();
            var object =$(obj).closest('.col-sm-12');
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
            }else if(type=='doctor_name'){
                if(value_str=='')  {
                    message = 'Please enter doctor name';
                }
            }else if(type=='service_name'){

                if(value_str==''){
                    message='Please enter service name';
                }
            }else if(type=='address'){
                if(value_str==''){
                    message='Please enter address';
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
        var counter = 30;


        $(document).on('click','#send_otp',function(e){
            if(formValidation($("#service_name"))){


                var $btn = $("[data-button=last]");
                var last = $("[data-button=last]").text();

                $.ajax({
                    type:'POST',
                    url: baseurl+"homes/send_otp",
                    data:{m:$("#mobile_number").val()},
                    beforeSend:function(){

                        if(resendInterval){
                            clearInterval(resendInterval);
                        }

                        $btn.text('Sending OTP');
                        $btn.attr('disabled',true);
                    },
                    success:function(response){
                        $btn.text('Resend OTP');

                        resendInterval =  setInterval(function () {
                            if(counter > 0){
                                $btn.text('Resend OTP ( '+  (counter--) +' )');
                            }else{
                                $btn.text('Resend OTP');
                                $btn.attr('disabled',false);
                            }
                        },1000);
                        if(response){
                            $("#otp_div").slideDown('500');
                            $("#otp_div").focus();
                            $("#otp_div").find('input').attr('data-t',response);
                        }

                    },
                    error: function(data){
                        $btn.text(last);
                        $btn.attr('disabled',false);
                    }
                });
            }
        });


        $(document).on('click','#finish_btn',function(e){
            if(btoa($("#otp").val())==$("#otp").attr('data-t')){
                var $btn = $("#finish_btn");
                var last = $("#finish_btn").text();

                $.ajax({
                    type:'POST',
                    url: baseurl+"homes/create_app",
                    data:$("#data_form").serialize(),
                    beforeSend:function(){
                        $btn.text('Please wait...');
                        $btn.attr('disabled',true);
                    },
                    success:function(response){
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
            }else{
                alet('Invalid OTP');
            }
        });


        $(document).on('input','#otp',function(e){
            $("#finish_btn").hide();
            $("[data-button=last]").show();
            if(btoa($(this).val())==$(this).attr('data-t')){
                $("#finish_btn").show();
                $("[data-button=last]").hide();
            }
        });

    });


</script>
</body>

</html>


