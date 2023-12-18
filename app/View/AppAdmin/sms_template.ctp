<?php
$login = $this->Session->read('Auth.User');


$country_list =$this->AppAdmin->countryDropdown();
?>

<style>
   /* textarea{height: 60px !important;}*/
   .variable_ul {
       display: block;
       float: left;
       width: 100%;
       padding: 0;
   }
    .variable_ul li{
        list-style: none;
        float: left;
        width: auto;
        margin: 3px 4px;
        font-weight: 600;
        background: #5965f5;
        color: #fff !important;
        padding: 2px 6px;
        border-radius: 5px;
    }
</style>

<div class="Home-section-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <?php echo $this->element('message'); ?>
                    <h3 class="screen_title">SMS Template Setting</h3>
                        <?php echo $this->Form->create('AppSmsTemplate',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h2>Appointment SMS</h2>
                        <div style="font-size:17px;display: block">Use the following variables to create your sms template. </div>
                            <ul class="variable_ul">
                                <li>[PATIENT_NAME]</li>
                                <li>[DOCTOR_NAME]</li>
                                <li>[APPOINTMENT_DATE]</li>
                                <li>[APPOINTMENT_TIME]</li>
                                <li>[OPD_START_TIME]</li>
                                <li>[OPD_END_TIME]</li>
                                <li>[TOKEN_NUMBER]</li>
                                <li>[APPOINTMENT_DAY]</li>
                                <li>[DOWNLOAD_URL]</li>
                                <li>[CANCEL_REASON]</li>
                                <li>[APPOINTMENT_REMINDER_MESSAGE]</li>
                                <li>[APPOINTMENT_REMINDER_DATE]</li>
                                <li>[APPOINTMENT_REMINDER_DAY]</li>
                                <li>[LIVE_TRACKER_URL]</li>
                                <li>[PAYMENT_RECEIPT_URL]</li>
                            </ul>
                        <span  style="display: block;color: green;">Ex.  Your booking for [APPOINTMENT_DATE] confirm successfully </span>
                        <div class="form-group">
                            <div class="col-sm-6 box_col">
                                <label>Booking SMS <span class="str_len"></span></label>
                                <?php echo $this->Form->input('appointment_booking_sms',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control')); ?>
                           </div>
                            <div class="col-sm-6 box_col">
                                <label>Cancel SMS <span class="str_len"></span></label>
                                <?php echo $this->Form->input('appointment_cancel_sms',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control')); ?>
                            </div>

                            <div class="col-sm-6 box_col">
                                <label>Reschedule SMS <span class="str_len"></span></label>
                                <?php echo $this->Form->input('appointment_reschedule_sms',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control')); ?>
                            </div>

                            <div class="col-sm-6 box_col">
                                <label>Reminder SMS <span class="str_len"></span></label>
                                <?php echo $this->Form->input('appointment_reminder_sms',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-10">
                            <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>
                            <input type="reset" class="btn btn-warning" value="Reset">
                            <input type="submit" class="btn btn-success" value="Save">
                        </div>
                    </div>
                        <?php echo $this->Form->end(); ?>
                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>
</div>

<style>
   .note{
       font-weight: 600;
       color: #000;
   }
    .box_col label{
        width: 100%;
    }
    .box_col label span{
        float: right;
    }
</style>


<script>
    $(document).ready(function(){



        $(document).on('change',"[name='data[AppointmentStaff][show_appointment_time]'], [name='data[AppointmentStaff][show_appointment_token]']",function(e){
            var time = $("[name='data[AppointmentStaff][show_appointment_time]']:checked").val();
            var token = $("[name='data[AppointmentStaff][show_appointment_token]']:checked").val();
            var data_type = $(this).attr('data_type');
            if(time =='NO' && token == 'NO'){
                if(data_type=='time'){
                    $("#show_appointment_timeYES").prop("checked", true);
                }else{
                    $("#show_appointment_tokenYES").prop("checked", true);
                }
                $.alert("Please select one of option YES from 'Can user see appointment time?' OR 'Can user see appointment time?'");
            }

        });


        var upload = false;
        var upload_file =false;
        $(".channel_tap a").removeClass('active');
        $("#v_add_channel").addClass('active');
        var is_image = $(".image_box").val();
        if(is_image != ""){
            $('.channle_img_box').css('background-image', "url('"+is_image+"')");
        }
        /* $("#v_add_channel").attr("href","javascript:void(0);");
         $("#v_add_channel").html("<i class='fa fa-pencil'> </i> Edit Channel");
         */


        var dob = $(".dob").val();
        if(dob =='0000-00-00'){
            $(".dob").val('');
        }
        $('.datepicker').datepicker({autoclose:true,format:'dd-mm-yyyy'});

        $(document).on('click','.edit_img_icon',function(e){
            $(".hidden_file_browse").trigger("click");
        });


        $(document).on('submit','#sub_frm',function(e){
            if(upload_file==true){
                $.alert('Please upload profile image');
                return false;
            }

        });

        $(document).on('change','.hidden_file_browse',function(e){
            if($(this).val()){
                $(".upload_media").show();
                readURL(this);
            }else{
                $(".upload_media").hide();
            }
        });

        $(document).on('change','#staff_type',function(e){

            if($(this).val()=="DOCTOR"){
                $(".doctor_section").slideDown(300);
                $(".reception_div").hide();
            }else{
                $(".doctor_section").slideUp(300);
                $(".reception_div").show();
            }
        });


        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.channle_img_box').css('background-image', "url('"+e.target.result+"')");
                    upload_file= true;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).on('change','.country',function(e){
            var country_id =$(this).val();
            if(country_id){
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/get_state_list",
                    data:{country_id:country_id},
                    beforeSend:function(){

                        $('.state_spin').show();
                        $('.city, .state').attr('disabled',true).html('');
                    },
                    success:function(data){
                        $('.state_spin').hide();
                        $('.state').html(data);;
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
                    url: baseurl+"app_admin/get_city_list",
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


        if($(".country").val()==""){
            // $(".country").trigger('change');
        }

        $(document).on('click','.upload_media',function(e){

            var formData = new FormData($("#sub_frm")[0]);
            var $btn = $(this);
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/upload_doctor_image",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                beforeSend:function(){
                    $(".file_error, .file_success").html("");
                    $('.channle_img_box').html("<img src='"+img_loader+"'>");
                },
                success:function(data){
                    data = JSON.parse(data);

                    //$btn.button('reset');
                    $('.channle_img_box').html("");
                    if(data.status==1){
                        $(".image_box").val(data.url);
                        //$(".channel_img").attr("src",data.url);
                        upload_file =false;
                        $(".file_success").html(data.message);
                    }else{
                        $(".file_error").html(data.message);
                    }
                },
                error: function(data){
                    //$btn.button('reset');
                    $('.channle_img_box').css('background-image', "url('"+oldImg+"')");
                    $(".file_error").html("Sorry something went wrong on server.");
                }
            });

        })

        var currentRequest = [];
        $(document).on('input','textarea',function(e){
            var obj = $(this).closest('.box_col').find('.str_len');
            var msg =$(this).val();
            var id =$(this).attr('id');
            if(msg){
            currentRequest[id] =  $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/count_credit",
                    data:{msg:msg},
                    beforeSend:function(){
                        if(currentRequest[id] != null) {
                            currentRequest[id].abort();
                        }
                    },
                    success:function(data){
                        var data = JSON.parse(data);
                        var str = data.sms+" SMS Credits For "+data.len+" Characters";
                        $(obj).html(str);
                    },
                });
            }else{
                var str = "0 SMS Credits For 0 Characters";
                $(this).closest('.box_col').find('.str_len').html(str);
            }

        });
        setTimeout(function () {
            $('textarea').each(function (index,value) {
                $(this).trigger('input');
            });
        },100);
    });
</script>






