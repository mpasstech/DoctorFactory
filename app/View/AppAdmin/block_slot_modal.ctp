<div class="modal fade" id="block_slot_modal" role="dialog">

    <?php
    $login = $this->Session->read('Auth.User');
    $user_data = $this->Session->read('Auth.User.User');
    $filter_array = $this->AppAdmin->get_doctor_filter_appointment_array($login['User']['thinapp_id']);

    ?>



    <style type="text/css">
        .append_token_slots li{
            list-style: none;
            width: 10%;
            float: left;
            border: 1px solid #d5cdcd;
            margin: 3px 7px;
            padding: 0px;
            cursor:pointer;
        }
        .token_number_span{
            width: 30%;
            background: #0000002b;
            padding: 0px;
            margin: 0px;
            display: inline-block;
            height: 100%;
            font-size: 15px;
            font-weight: 600;
            text-align: center;
            float: left;
        }
        .BLOCK_BOOKED{
            background: #78f704;
            border-color: #78f704;
            color: #fff;
        }
        .BLOCK_BLOCKED{
            background: #9765d8;
            border-color: #9765d8;
            color: #fff;
        }


        .token_time_span{
            width: 70%;
            display: inline-block;
            float: left;
            margin: 0px;
            padding: 0px;
            text-align: center;
        }
        #block_slot_modal .form-control {
            width: 100%;
            height: 30px;
            padding: 1px 3px;
        }
        .loader_box{
            background: #fff;
            padding: 0px;
            text-align: center;
            left: 50%;
            position: absolute;
            top: 0px;
            z-index: 1;
            width: 1%;
            font-size: 20px;
            display: block;

        }
        .append_message{
            text-align: center;
            font-size: 20px;
            font-weight: 400;
            color: red;
            width: 100%;
            display: grid;
        }
        #block_slot_modal .col-lg-12{
            background: #fff;
            padding: 0;
        }
        #block_slot_modal .radio_lbl_vac button{
            float: right;
            margin: 0 2px;
            font-size: 14px;
            padding: 0px 16px;
        }
        #block_slot_modal #message_box{
            width: 85%;
            float: left;
        }
        #block_slot_modal .action_div{
            padding: 6px 7px;
            display: none;
        }

        #block_slot_modal #slot_save_btn,
        #block_slot_modal #slot_refresh_btn{
            margin: 0px 2px;
            padding: 3px 14px;
            font-size: 15px;
            float: left;
        }

    </style>
    <div class="modal-dialog modal-md" style="width: 95%;">
        <div class="modal-content">
            <div class="modal-header" style="padding: 8px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Block Appointment Slot</h4>
            </div>
            <div class="modal-body" style="padding: 0px;">



                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background: #fff; padding: 0px;">
                    <div class="form-group">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2  block_append_doctor">
                            <label>Select Doctor</label>
                            <?php echo $this->Form->input('doctor',array('id'=>'block_doctor_drp','type'=>'select','label'=>false,'options'=>array(),'class'=>'form-control')); ?>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 block_append_service">
                            <label>Select Service</label>
                            <?php echo $this->Form->input('service',array('id'=>'block_service_drp','type'=>'select','label'=>false,'empty'=>'Select address','options'=>array(),'class'=>'form-control')); ?>

                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3  block_append_address">

                            <label>Select Address</label>
                            <?php echo $this->Form->input('address',array('id'=>'block_address_drp','type'=>'select','label'=>false,'empty'=>'Select address','options'=>array(),'class'=>'form-control')); ?>

                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 block_append_date">

                            <label>Select Date</label>
                            <div class="input-group">
                                <span class="input-group-addon app_slot_span"><span class="glyphicon glyphicon-calendar"></span></span>
                                <input data-date-format = 'dd/mm/yyyy' type="text" id="block_appointment_date" class="form-control" placeholder="Date" name="block_appointment_date">
                            </div>

                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <label>Select Action</label>
                            <div class="radio_lbl_vac">
                                <label class="radio-inline">
                                    <input type="radio" value="BLOCK_ALL" name="optradio" id="block_all">Block All
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" value="UNBLOCK_ALL" name="optradio" id="unblock_all">Unblock All
                                </label>
                            </div>

                        </div>


                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 loader_box">
                    <span class="fa fa-spinner fa-spin slot_loader"></span>
                </div>

                <div class="append_message"></div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group append_token_slots">

                    </div>
                </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">

                        <div class="form-group action_div">
                            <input type="text" class="form-control" id="message_box" maxlength="150" placeholder="Type your message here..">
                            <button type="button" class="btn btn-success" id="slot_save_btn"><i class="fa fa-save"></i> Save</button>
                            <button type="button" class="btn btn-warning" id="slot_refresh_btn"><i class="fa fa-refresh"></i> Reset</button>
                        </div>


                    </div>



            </div>


        </div>
    </div>
    <script type="text/javascript">
        $(function () {


            var tempArray = <?php echo json_encode($filter_array); ?>;
            function block_create_doctor_dropdown(){

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
                    $("#block_doctor_drp").html(doctor_string);
                    setTimeout(function(){$("#block_doctor_drp").trigger('change');},10);
                }


            }
            function block_create_service_dropdown(doctor_id){

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
                            $("#block_service_drp").html(option);
                            setTimeout(function(){$("#block_service_drp").trigger('change');},10);
                            break;
                        }
                    }
                }

            }
            function block_create_address_dropdown(service_id,doctor_id){
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
                                        $("#block_address_drp").html(option);
                                        setTimeout(function(){$("#block_address_drp").trigger('change');},10);

                                    }
                                }

                            }
                            break;
                        }
                    }
                }
            }

            block_create_doctor_dropdown();

            $('#block_doctor_drp').val($('#doctor_drp').val());
            $('#block_service_drp').val($('#service_drp').val());
            $('#block_address_drp').val($('#address_drp').val());
            $('#block_appointment_date').val($('#appointment_date').val());


            $(document).off('change','#block_doctor_drp');
            $(document).on('change','#block_doctor_drp',function(e){

                var doctor_id = $(this).val();
                block_create_service_dropdown(doctor_id);
            });
            $(document).off('change','#block_service_drp');
            $(document).on('change','#block_service_drp',function(e){
                var service_id = $(this).val();
                var doctor_id = $('#block_doctor_drp').val();
                block_create_address_dropdown(service_id,doctor_id);
            });
            $(document).off('change','#block_address_drp');
            $(document).on('change','#block_address_drp',function(e){
                $("#block_appointment_date").trigger('changeDate');
            });

            $(document).off('click','#block_slot_modal .append_token_slots li');
            $(document).on('click','#block_slot_modal .append_token_slots li',function(e){
               if($(this).attr('class') == $(this).attr('last-flag')){
                   if($(this).attr('last-flag')=="BLOCK_BLOCKED"){
                       $(this).attr('class','BLOCK_AVAILABLE');
                   }else{
                       $(this).attr('class','BLOCK_BLOCKED');
                   }
                }else{
                    $(this).attr('class',$(this).attr('last-flag'));
                }
                $("#slot_radio").prop('checked',true);
            });



            $(document).off('click','#slot_refresh_btn');
            $(document).on('click','#slot_refresh_btn',function(e){
                $("#block_slot_modal .append_token_slots li").each(function () {
                    $("#slot_radio").prop('checked',true);
                    $(this).attr('class',$(this).attr('last-flag'));
                });
            });




            $(document).off('change','#block_slot_modal [name="optradio"]');
            $(document).on('change','#block_slot_modal [name="optradio"]',function(e){
                if($(this).is(':checked')){
                    if($(this).val() == 'BLOCK_ALL'){
                        $("#block_slot_modal .append_token_slots li").attr('class','BLOCK_BLOCKED');
                    }else if($(this).val() == 'UNBLOCK_ALL'){
                        $("#block_slot_modal .append_token_slots li").each(function () {
                            $(this).attr('class','BLOCK_AVAILABLE');
                        });
                    }
                }
            });

            $("#block_appointment_date").datepicker('setDate',new Date()).on('changeDate', function(ev){
                $('#block_appointment_date').datepicker('hide');
                load_slots();
            });

            function load_slots(){
               var doctor_id = $('#block_doctor_drp').val();
               var service_id = $('#block_service_drp').val();
               var address_id = $('#block_address_drp').val();
               var date =  $("#block_appointment_date").val();
               obj =  $("#block_appointment_date");
               $.ajax({
                   type:'POST',
                   url: baseurl+"app_admin/load_slot_data",
                   data:{return_array:'1',address_id:btoa(address_id),doctor_id:btoa(doctor_id),service_id:btoa(service_id),date:date},
                   beforeSend:function(){
                    $(".loader_box").show();
                       $("#block_slot_modal .append_message").html("");
                   },
                   success:function(data){
                       var response = JSON.parse(data);
                       $(".loader_box").hide();

                       if(response.length > 0){
                           var string = "";
                           $.each(response,function(index, value){
                               var flag = "BLOCK_"+value.flag;
                               if(value.has_token =='YES' && value.sub_token =="NO" && value.custom_token == "NO"){
                                   string += "<li last-flag='"+flag+"' class='"+flag+"'><span class='token_number_span'>"+value.token+"</span><span class='token_time_span'>"+value.time+"</span></li>";
                               }
                           });
                           $("#block_slot_modal .append_token_slots").html(string);
                           $("#block_slot_modal .action_div").show();

                       }else{
                           $("#block_slot_modal .append_token_slots").html("");
                           $("#block_slot_modal .action_div").hide();
                           $("#block_slot_modal .append_message").html("Slots are not available");
                       }
                   },
                   error: function(data){
                       $(".loader_box").hide();
                       $("#block_slot_modal .action_div").hide();
                       $("#block_slot_modal .append_token_slots").html("");
                       $("#block_slot_modal .append_message").html("Sorry something went wrong on server.");
                   }
               });
           }


            $(document).off('click','#slot_save_btn');
            $(document).on('click','#slot_save_btn',function(e){

                var doctor_id = $('#block_doctor_drp').val();
                var service_id = $('#block_service_drp').val();
                var address_id = $('#block_address_drp').val();
                var date =  $("#block_appointment_date").val();
                var message =  $("#message_box").val();
                var total_slot = $("#block_slot_modal .append_token_slots li").length;
                var blocked_length = $("#block_slot_modal .BLOCK_BLOCKED").length;


                var block_by =  'SLOT';
                var slot_string = '';
                var blocked_array = [];
                if(blocked_length == 0){
                    block_by ='SLOT';
                    slot_string = 'CLEAR';
                }else{
                    if(blocked_length == total_slot){
                        block_by ='DATE';
                    }
                    $("#block_slot_modal .append_token_slots li").each(function () {
                        if($(this).attr('class') == 'BLOCK_BLOCKED'){
                            blocked_array.push($(this).find('.token_time_span').text());
                        }
                    });
                    slot_string = blocked_array.join();
                }

                var thin_app_id =  "<?php echo $user_data['thinapp_id']; ?>";
                var mobile =  "<?php echo $user_data['mobile']; ?>";
                var user_id =  "<?php echo $user_data['id']; ?>";
                var role_id =  "<?php echo $user_data['role_id']; ?>";
                var send_data = {action_from:'WEB',thin_app_id:thin_app_id,message:message,mobile:mobile,user_id:user_id,role_id:role_id,slot_string:slot_string,block_by:block_by,address_id:(address_id),doctor_id:(doctor_id),service_id:(service_id),date:date};

                var $btn = $(this);
                var id = $(this).attr('data-id');
                var row = $(this).closest('tr');
                var dialog = $.confirm({
                    title: 'Save',
                    content: 'Are you sure you want to save this setting?',
                    keys: ['enter', 'shift'],
                    buttons:{
                        Yes: {
                            keys: ['enter'],
                            action:function(e){
                                var $btn2 = $(this);
                                $.ajax({
                                    type:'POST',
                                    url: baseurl + "services/blocked_appointment_slot",
                                    data:JSON.stringify(send_data),
                                    beforeSend:function(){
                                        $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                                        $btn2.button('loading');
                                    },
                                    success:function(data){
                                        dialog.close();
                                        $btn.button('reset');
                                        $btn2.button('reset');
                                        var response = JSON.parse(data);
                                        if(response.status==1){
                                            $("#appointment_date").trigger('changeDate');
                                            $("#block_slot_modal").modal('hide');
                                        }else{
                                            $.alert(response.message);
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



        })
    </script>
</div>
