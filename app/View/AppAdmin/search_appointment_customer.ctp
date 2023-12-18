<div class="modal fade"  id="search_cus_modal" role="dialog" keyboard="true">

<div class="modal-dialog modal-sm " >
    <div class="modal-content form-horizontal booking_form_content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title">Book Appointment</h5>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-3 info_section">
                    <div class="image_container">
                        <img class="doctor_image" src="https://s3-ap-south-1.amazonaws.com/mengage/logo/app_logo_134.png" />
                    </div>
                    <ul>
                        <li><label >Token number </label> <span id="token_number_lbl"></span></li>
                    </ul>
                    <div class="time_section">
                        <li>
                            <label><i class="fa fa-calendar"></i> : </label> <span id="date_lbl"></span>
                        </li>
                        <li>
                            <label><i class="fa fa-clock-o"></i> : </label> <span id="time_lbl"></span>
                        </li>
                    </div>



                </div>
                <div class="col-sm-9 search_mobile_main_div">
                    <span class="input-group-addon app_slot_span slot_err_msg loader_icon_on_booking"><span class="fa fa-spinner fa-spin customer_loading"></span></span>


                    <div class="row search_top_box">
                        <div class="col-md-5" style="padding: 0px 4px;">
                            <div class="note_message">Note: Enter 9999999999 If Mobile Number Is Not Available.</div>
                            <div class="input-group">
                                <span class="input-group-addon app_slot_span"><!--<span class="glyphicon glyphicon-phone"></span>--> +91</span>
                                <input flag="number" maxlength="10" type="text" class="form-control app_search_customer" id="app_search_customer" list="mobileDataList" placeholder="Enter Patient Mobile Number" >
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="note_message">&nbsp;</div>
                            <div class="input-group">
                                <span class="input-group-addon app_slot_span">UHID</span>
                                <input flag="uhid" type="text" class="form-control app_search_customer app_search_customer_uhid" id="app_search_customer_uhid" placeholder="Enter Patient UHID" >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="note_message">&nbsp;</div>
                            <div class="input-group">
                                <span class="input-group-addon app_slot_span"><i class="fa fa-user"></i> </span>
                                <input flag="name" type="text" class="form-control app_search_customer app_search_customer_name" id="app_search_customer_name" placeholder="Enter Patient Name" >
                            </div>
                        </div>




                        <datalist id="mobileDataList">
                            <?php foreach ($appointmentMobiles AS $key => $value) { ?>
                                <option value="<?php echo str_replace('+91','',$value); ?>" ><?php echo str_replace('+91','',$value); ?></option>
                            <?php } ?>
                        </datalist>
                    </div>
                    <div class="row option_dropdown_div" style="display:none;">
                        <div class="col-md-3 append_doctor">
                            <label>Select Doctor</label>
                            <?php echo $this->Form->input('doctor',array('id'=>'dialog_doctor_drp','type'=>'select','label'=>false,'options'=>array(),'class'=>'form-control')); ?>
                        </div>
                        <div class="append_service">
                            <div class="col-md-3">
                                <label>Select Service</label>
                                <?php echo $this->Form->input('service',array('id'=>'dialog_service_drp','type'=>'select','label'=>false,'empty'=>'Select address','options'=>array(),'class'=>'form-control')); ?>
                            </div>
                        </div>
                        <div class="append_address">
                            <div class="col-md-5">
                                <label>Select Address</label>
                                <?php echo $this->Form->input('address',array('id'=>'dialog_address_drp','type'=>'select','label'=>false,'empty'=>'Select address','options'=>array(),'class'=>'form-control')); ?>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12 dynamic_option_box">
                        <div class="select_patient_option">

                        </div>
                        <div class="error_messages_box">
                             <div class="app_error_msg"></div>
                        </div>
                   </div>



                </div>
                <div class="col-sm-12 list_load_section">
                    <div id="load_customer_div">


                    </div>

                    <div class="add_cus_div_app_load">
                         <?php echo $booking_form; ?>
                    </div>

                </div>

            </div>



        </div>
    </div>
</div>
<style>

    .booking_form_content{
        min-height: 600px;
    }
    .time_section li{
        width: 100%;
        float: left;
        text-align: left;
        list-style: none;
    }

    .info_section ul{
        float: left;
        list-style: none;
        width: 35%;
        padding: 0px;
        margin: 0px;
    }
    .info_section ul li label{
        display: block;
        text-align: center;
        font-size: 12px;
        text-transform: uppercase;
    }
    .time_section{
        display: block;
        float: left;
        width: 50%;
        padding: 1px 8px;
        margin-top: 0px;
        text-align: center;
    }

    .time_section label{
        font-size: 18px;
    }
    .time_section span{
       font-size: 17px;
       font-weight: 600;
       color: #6693ad;
    }

    #mobileDataList{
        height:50px !important;
        max-height:80px !important;
        overflow-y:auto;
        display:none !important;
    }

    .info_section ul li span{
        font-size: 28px;
        text-align: center;
        width: 100%;
        font-weight: 600;
        padding: 0px 0px;
        display: block;
        color: #00b300;
    }
    .image_container{
        float: left;
        width: 15%;
        text-align: center;
    }
    .doctor_image{
        width: 45px;
        height: 45px;
        border-radius: 70px;
        margin: 5px 0px;
    }
    .note_message {
        text-align: center;
        font-size: 11px;
        color: red;
        line-height: 16px;
        margin-bottom: 0px;
    }
    .slot_err_msg {
        background: none;
        border: none;
        border-right-width: medium;
        border-right-style: none;
        border-right-color: currentcolor;
    }
    .load_customer_div{
        padding: 0px 10px;
    }

    .form-control {
        height: 32px;
        padding: 3px 4px;

    }

    #search_cus_modal .modal-dialog{
        width:92% !important;
        margin: 10px auto !important;
    }

    #search_cus_modal .modal-dialog .modal-body {
        padding: 6px !important;
    }
    #search_cus_modal .modal-header{
        padding: 8px !important;
    }


    #search_cus_modal .search_top_box{
        border: none;
        margin: 0px auto 0px auto;
    }


    .load_customer_div{
        border-top: 2px dashed #f1f1f1;
        padding-top: 7px;
    }


    .load_customer_div{
        padding: 0px 12px;
    }
</style>

<script>

    $(function(){

        setTimeout(function () {
            if($("#search_cus_modal").attr('data-type') == 'WALK-IN' || $("#search_cus_modal").attr('data-type') == 'EMERGENCY'){
                $(".list_load_section").addClass("margin_top_div");
            }else{
                $(".list_load_section").removeClass("margin_top_div");
            }
        },100);


        /*$('#AppointmentCustomerCountryId').magicSuggest({}).disable();
        $('#AppointmentCustomerStateId').magicSuggest({}).disable();
        $('#AppointmentCustomerCityId').magicSuggest({}).disable();
        */

        $(".add_cus_div_app_load input").prop('readonly',true);
        $(".add_cus_div_app_load select").prop('disabled',true);


        var tempArray = <?php echo json_encode($filter_array); ?>;
        function dialog_create_doctor_dropdown(set_value){

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
                $("#dialog_doctor_drp").html(doctor_string);
                var doctor_id = $("#dialog_doctor_drp").val();
                if(set_value === true){
                    if($('#doctor_drp > option:selected').val()){
                        doctor_id = $('#doctor_drp > option:selected').val();
                        $("#dialog_doctor_drp").val(doctor_id);
                    }
                }
                dialog_create_service_dropdown(doctor_id,set_value);

            }


        }
        function dialog_create_service_dropdown(doctor_id,set_value){
            var option="";
            for (var doctor_key in tempArray) {
                if (tempArray.hasOwnProperty(doctor_key)) {

                    $(".doctor_image").attr('src',tempArray[doctor_key].doctor_image);
                    if(doctor_key == doctor_id){
                        object = tempArray[doctor_key]['service'];
                        for (var service_key in object) {
                            if (object.hasOwnProperty(service_key)) {
                                option += "<option value='"+service_key+"'>"+object[service_key].service_name+"</option>";
                            }
                        }
                        $("#dialog_service_drp").html(option);
                        var service_id = $("#dialog_service_drp").val();
                        var doctor_id = $("#dialog_doctor_drp").val();
                        if(set_value === true){
                            if($('#service_drp > option:selected').val()){
                                service_id = $('#service_drp > option:selected').val();
                                $("#dialog_service_drp").val(service_id);
                            }
                        }
                        dialog_create_address_dropdown(service_id,doctor_id,set_value);
                        break;
                    }
                }
            }

        }
        function dialog_create_address_dropdown(service_id,doctor_id,set_value){
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
                                    $("#dialog_address_drp").html(option);
                                    if(set_value ===true){
                                        if($('#address_drp > option:selected ').val()){
                                            $("#dialog_address_drp").val($('#address_drp > option:selected ').val());
                                        }
                                    }

                                }
                            }
                        }
                        break;
                    }
                }
            }
        }

        $(document).off('change','#dialog_doctor_drp');
        $(document).on('change','#dialog_doctor_drp',function(e){
            var doctor_id = $(this).val();
            dialog_create_service_dropdown(doctor_id,false);
        });

        $(document).off('change','#dialog_service_drp');
        $(document).on('change','#dialog_service_drp',function(e){
            var service_id = $(this).val();
            var doctor_id = $('#dialog_doctor_drp').val();
            dialog_create_address_dropdown(service_id,doctor_id,false);
        });

        setTimeout(function(){
            $("#search_cus_modal #app_search_customer").focus();
            dialog_create_doctor_dropdown(true);
            var appointment_type = $("#search_cus_modal").attr('data-type');
            if(appointment_type == "WALK-IN" || appointment_type == "EMERGENCY"){
                $(".option_dropdown_div").show();
            }else{
                if(!$("#dialog_doctor_drp").val() || !$("#dialog_service_drp").val() || !$("#dialog_address_drp").val())

                $(".option_dropdown_div").show();
            }
            var sub_count = "";
            if(appointment_type == "SUB_TOKEN"){
                var slot = atob($("#search_cus_modal").attr('data-slot'));
                var length = $("#load_slot_div li .time_slot_label[data-time='"+slot+"']").length;
                sub_count = "."+length;
                var queue = $("#search_cus_modal").attr('data-queue');
                if(queue){
                    if(queue != 0){
                        $("#token_number_lbl").html(atob(queue)+sub_count);
                    }
                }
            }else{
                var queue = $("#search_cus_modal").attr('data-queue');
                if(queue){
                    if(queue != 0){
                        $("#token_number_lbl").html(atob(queue));
                    }else{
                        if(appointment_type=="EMERGENCY"){
                            $("#token_number_lbl").html('E');
                        }else{
                            $("#token_number_lbl").html('WI');
                        }
                    }
                }
            }

            var booking_date = "<?php echo $date; ?>";
            var time = ($("#search_cus_modal").attr('data-slot'));
            if(time){
                time = atob(time);
            }else{
                time = "<?php echo $current_time; ?>";
            }

            $("#date_lbl").html(booking_date);
            $("#time_lbl").html(time);


        },50);
        $(".customer_loading").hide();

        isSearching = 'NO';

        var currentRequest=null;
        $(document).off('input',"#app_search_customer, #app_search_customer_uhid, #app_search_customer_name" );
        $(document).on('input',"#app_search_customer, #app_search_customer_uhid, #app_search_customer_name", function(e){
            var flag = $(this).attr('flag');

            if(flag=='number'){
                $("#app_search_customer_uhid, #app_search_customer_name").val('');
            }else if(flag=='uhid'){
                $("#app_search_customer, #app_search_customer_name").val('');

            }else if(flag=='name'){
                $("#app_search_customer, #app_search_customer_uhid").val('');
            }

            var search = $(this).val();
            var in_process =false;
            var $btn = $(this);

            if( ($("#load_customer_div div").length == 0 &&  flag == 'number' && $.isNumeric(search) && search.length == 10) || (flag =='uhid' &&  search.length < 17 ) || (flag =='name' &&  search.length > 2 && search.length < 15 ) ){
                $(".app_error_msg").html('');
                if(flag =='number'){
                    search = "+91"+search;
                }
                $(".file_error").fadeOut('slow');
                if(isSearching == 'NO')
                {

                var prev_cancel = false;
                currentRequest =  $.ajax({
                        type:'POST',
                        url: baseurl+"app_admin/load_search_customer_list",
                        data:{flag:flag,search:search},
                        beforeSend:function(){
                            if(currentRequest != null) {
                                currentRequest.abort();
                                prev_cancel =true;
                            }

                            if(flag == 'number'){
                                $btn.attr('disabled',true);
                                isSearching = 'YES';
                            }
                            $(".app_error_msg").fadeOut('slow');
                            $(".customer_loading").show();
                        },
                        success:function(data){
                            isSearching = 'NO';
                            $("#search_cus_modal #load_customer_div").html(data);
                            if($('.table-wrapper-scroll-y').length == 0 && ( $($btn).attr('id') == 'app_search_customer') ) {


                                $("#search_cus_modal .select_patient_option").find("input[value='CUSTOMER']").prop( "checked", true ).delay(5).trigger('click');


                            }
                            $btn.attr('disabled',false);
                            $(".customer_loading").hide();
                            $($btn).focus();

                        },
                        error: function(data){
                            isSearching = 'NO';
                            $btn.attr('disabled',false);
                            $(".customer_loading").hide();
                            if(prev_cancel === false){
                                $(".app_error_msg").html("Sorry something went wrong on server.").fadeIn('slow');
                            }

                        }
                    });
                }
            }else{
                if(isSearching=="NO"){
                    if(flag == 'number'){
                        $(".app_error_msg").html("Please enter valid 10 digit mobile number.").fadeIn('slow');
                    }else if(flag == 'uhid'){
                        //$(".app_error_msg").html("Please enter minimum 7 digit UHID.").fadeIn('slow');
                    }
                }
                $("#search_cus_modal #load_customer_div").html('');
            }


        });



    })


</script>
</div>

